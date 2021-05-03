<?php
$sql = "SELECT 
            `tbl_customer_customer`.*,
            `tbl_bank_info`.bank_full_name,
            `tbl_bank_info`.bank_short_name,
            `tbl_bank_info`.bank_code
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
} else {
    returnError("Nhập id_customer");
}

// echo $sql;
// exit();
$customer_arr = array();
$customer_arr['success'] = 'true';

$customer_arr['data'] = array();
$result = db_qr($sql);
$nums = db_nums($result);



if ($nums > 0) {
    while ($row = db_assoc($result)) {
        $customer_paymented = get_customer_paymented_in_day($row['id']);
        $customer_item = array(
            'id_customer' => $row['id'],
            'type_account' => "customer",
            'type_customer' => 'customer',
            'id_bank' => ($row['id_bank'] != 0) ? $row['id_bank'] : "",
            'bank_name' => (!empty($row['bank_full_name'])) ? $row['bank_full_name'] : "",
            'bank_short_name' => (!empty($row['bank_short_name'])) ? $row['bank_short_name'] : "",
            'customer_name' => htmlspecialchars_decode($row['customer_fullname']),
            'customer_code' => htmlspecialchars_decode($row['customer_code']),
            'customer_phone' => htmlspecialchars_decode($row['customer_phone']),
            'customer_introduce' => htmlspecialchars_decode($row['customer_introduce']),
            'customer_cert_no' => htmlspecialchars_decode($row['customer_cert_no']),
            'customer_cert_img' => htmlspecialchars_decode($row['customer_cert_img']),
            'customer_account_no' => (!empty($row['customer_account_no'])) ? $row['customer_account_no'] : "",
            'customer_account_holder' => (!empty($row['customer_account_holder'])) ? $row['customer_account_holder'] : "",
            'customer_account_img' => (!empty($row['customer_account_img'])) ? $row['customer_account_img'] : "",
            'customer_wallet_bet' => htmlspecialchars_decode($row['customer_wallet_bet']),
            'customer_wallet_payment' => htmlspecialchars_decode($row['customer_wallet_payment']),
            'customer_wallet_rewards' => htmlspecialchars_decode($row['customer_wallet_rewards']),
            'customer_limit_payment' => htmlspecialchars_decode($row['customer_limit_payment']),
            'customer_virtual' => htmlspecialchars_decode($row['customer_virtual']),
            'customer_disable' => htmlspecialchars_decode($row['customer_disable']),
            'customer_paymented' => (!empty($customer_paymented)) ? $customer_paymented : "0",

        );

        array_push($customer_arr['data'], $customer_item);
    }
    reJson($customer_arr);
} else {
    returnError("Không có khách hàng");
}
