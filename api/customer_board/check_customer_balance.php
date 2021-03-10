<?php

$sql = "SELECT * FROM tbl_customer_customer
        WHERE 1=1";

if (isset($_REQUEST['id_customer'])) {
    if ($_REQUEST['id_customer'] == '') {
        unset($_REQUEST['id_customer']);
        returnError("type_manager");
    } else {
        $id_customer = $_REQUEST['id_customer'];
        $sql .= " AND `id` = '{$id_customer}'";
    }
} else {
    returnError("id_customer");
}


$customer_arr = array();
$customer_arr['success'] = 'true';

$customer_arr['data'] = array();
$result = db_qr($sql);
$nums = db_nums($result);


if ($nums > 0) {
    while ($row = db_assoc($result)) {
        $customer_item = array(
            'id_customer' => $row['id'],
            'customer_wallet_bet' => htmlspecialchars_decode($row['customer_wallet_bet']),
            'customer_wallet_payment' => htmlspecialchars_decode($row['customer_wallet_payment']),
        );
        array_push($customer_arr['data'], $customer_item);
    }
    reJson($customer_arr);
} else {
    returnError("Không có khách hàng");
}
