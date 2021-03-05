<?php
$sql = "SELECT 
            `tbl_customer_customer`.*
            `tbl_bank_info`.*
            FROM `tbl_customer_customer`
            LEFT JOIN `tbl_bank_info` ON `tbl_bank_info`.`id` = `tbl_customer_customer`.`id_bank`
            WHERE 1=1";

if (isset($_REQUEST['id_customer'])) {
    if ($_REQUEST['id_customer'] == '') {
        unset($_REQUEST['id_customer']);
        returnError("Nhập id_customer");
    } else {
        $id_customer = $_REQUEST['id_customer'];
        $sql .= " AND `tbl_customer_customer`.`id` = '{$id_customer}'";
    }
}
returnError("Nhập id_customer");

$customer_arr = array();

if (empty($error)) {
    $customer_arr['success'] = 'true';

    $customer_arr['data'] = array();
    $result = db_qr($sql);
    $nums = db_nums($result);


    if ($nums > 0) {
        while ($row = db_assoc($result)) {
            $customer_item = array(
                'id_customer' => $row['id'],
                'id_bank' => $row['id_bank'],
                'bank_name' => $row['bank_name'],
                'customer_introduce' => htmlspecialchars_decode($row['customer_introduce']),
                'customer_name' => htmlspecialchars_decode($row['customer_fullname']),
                'customer_code' => htmlspecialchars_decode($row['customer_code']),
                'customer_phone' => htmlspecialchars_decode($row['customer_phone']),
                'customer_introduce' => htmlspecialchars_decode($row['customer_introduce']),
                'customer_cert_no' => htmlspecialchars_decode($row['customer_cert_no']),
                'customer_cert_img' => htmlspecialchars_decode($row['customer_cert_img']),
                'customer_account_no' => htmlspecialchars_decode($row['customer_account_no']),
                'customer_account_holder' => htmlspecialchars_decode($row['customer_account_holder']),
                'customer_account_img' => htmlspecialchars_decode($row['customer_account_img']),
                // 'customer_wallet_bet' => htmlspecialchars_decode($row['customer_wallet_bet']),
                // 'customer_wallet_payment' => htmlspecialchars_decode($row['customer_wallet_payment']),
            );

            array_push($customer_arr['data'], $customer_item);
        }
        reJson($customer_arr);
    } else {
        returnSuccess("Không có khách hàng");
    }
}
