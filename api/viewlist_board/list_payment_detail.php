<?php

$sql = "SELECT
            tbl_customer_customer.customer_fullname,
            tbl_customer_customer.customer_account_no,
            tbl_customer_customer.customer_account_holder,
            tbl_customer_customer.customer_account_img,
            tbl_customer_customer.customer_cert_img,
            tbl_customer_customer.customer_cert_no,
            tbl_customer_customer.customer_limit_payment,
            tbl_customer_customer.customer_phone,
            tbl_request_payment.*,
            tbl_bank_info.id as id_bank,
            tbl_bank_info.bank_code as bank_code,
            tbl_bank_info.bank_full_name as bank_full_name,
            tbl_bank_info.bank_short_name as bank_short_name
            FROM tbl_customer_customer 
            LEFT JOIN tbl_request_payment ON tbl_request_payment.id_customer = tbl_customer_customer.id
            LEFT JOIN tbl_bank_info ON tbl_customer_customer.id_bank = tbl_bank_info.id
            WHERE 1=1
        ";

if (isset($_REQUEST['id_request'])) {
    if ($_REQUEST['id_request'] == '') {
        unset($_REQUEST['id_request']);
        returnError("Nhập id_request");
    } else {
        $id_request = $_REQUEST['id_request'];
        $sql .= " AND `tbl_request_payment`.`id` = '{$id_request}'";
    }
} else {
    returnError("Nhập id_request");
}

$request_arr = array();
$request_arr['success'] = 'true';
$request_arr['data'] = array();
$result = db_qr($sql);
$nums = db_nums($result);

if ($nums > 0) {
    while ($row = db_assoc($result)) {
        $customer_paymented = get_customer_paymented_in_day($row['id_customer']);
        $request_item = array(
            'id_request' => $row['id'],
            'id_customer' => $row['id_customer'],
            'id_bank' => ($row['id_bank'] != 0) ? $row['id_bank'] : "",
            'bank_name' => (!empty($row['bank_full_name'])) ? $row['bank_full_name'] : "",
            'bank_short_name' => (!empty($row['bank_short_name'])) ? $row['bank_short_name'] : "",
            'customer_name' => htmlspecialchars_decode($row['customer_fullname']),
            'customer_phone' => htmlspecialchars_decode($row['customer_phone']),
            'request_code' => $row['request_code'],
            'request_status' => $row['request_status'],
            'request_created' => date("d/m/Y H:i", $row['request_created']),
            'request_comment' => (!empty($row['request_comment'])) ? $row['request_comment'] : "",
            'request_img' => (!empty($row['request_img'])) ? $row['request_img'] : "",
            'request_value' => $row['request_value'],
            'request_count' => "1",
            'request_fee' =>$row['request_fee'],
            'request_actural' => strval(((100-(int)$row['request_fee'])*(int)$row['request_value'])/100),
            'customer_account_holder' => $row['customer_account_holder'],
            'customer_account_no' => $row['customer_account_no'],
            'customer_account_img' => $row['customer_account_img'],
            'customer_cert_img' => $row['customer_cert_img'],
            'customer_limit_payment' => $row['customer_limit_payment'],
            'customer_paymented' => (isset($customer_paymented) && !empty($customer_paymented)) ? $customer_paymented : "0",
        );
        switch($row['request_fee']){
            case '0':
                $request_item['request_count'] = "1";
                break;

                case '3':
                    $request_item['request_count'] = "2";
                    break;

                    case '5':
                        $request_item['request_count'] = "3";
                        break;
                        case '8':
                            $request_item['request_count'] = "4";
                            break;
                            case '10':
                                $request_item['request_count'] = "5";
                                break;
                                case '15':
                                    $request_item['request_count'] = "6";
                                    break;
                                    default :
                                        $request_item['request_count'] = "Nhiều hơn 7 lần";
                                        break;
        }

        array_push($request_arr['data'], $request_item);
    }
}
reJson($request_arr);
