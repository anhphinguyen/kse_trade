<?php

$sql = "SELECT
            tbl_customer_customer.customer_fullname,
            tbl_customer_customer.customer_account_no,
            tbl_customer_customer.customer_account_holder,
            tbl_customer_customer.customer_account_img,
            tbl_customer_customer.customer_cert_img,
            tbl_customer_customer.customer_cert_no,
            tbl_request_payment.*,
            tbl_bank_info.id as id_bank,
            tbl_bank_info.bank_code as bank_code,
            tbl_bank_info.bank_full_name as bank_full_name,
            tbl_bank_info.bank_short_name as bank_short_name,
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
        $request_item = array(
            'id_request' => $row['id'],
            'id_customer' => $row['id_customer'],
            'id_bank' => $row['id_bank'],
            'bank_name' => $row['bank_name'],
            'customer_name' => htmlspecialchars_decode($row['customer_fullname']),
            'request_code' => $row['request_code'],
            'request_status' => $row['request_status'],
            'request_created' => $row['request_created'],
            'request_comment' => (!empty($row['request_comment']))?$row['request_comment']:"",
            'request_img' => (!empty($row['request_img']))?$row['request_img']:"",
            'request_value' => $row['request_value'],
            'bank_short_name' => $row['bank_short_name'],
            'customer_account_holder' => $row['customer_account_holder'],
            'customer_account_no' => $row['customer_account_no'],
            'customer_account_img' => $row['customer_account_img'],
            'customer_cert_img' => $row['customer_cert_img'],
        );

        array_push($request_arr['data'], $request_item);
    }
    reJson($request_arr);
} else {
    returnSuccess("Không tìm thấy yêu cầu");
}
