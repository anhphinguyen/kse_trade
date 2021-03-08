<?php

$sql = "SELECT * FROM `tbl_customer_customer` WHERE 1=1";

if (isset($_REQUEST['id_customer'])) {
    if ($_REQUEST['id_customer'] == '') {
        unset($_REQUEST['id_customer']);
        returnError("Nhập id_customer");
    } else {
        $id_customer = $_REQUEST['id_customer'];
        $sql .= " AND `tbl_customer_customer`.`id` = '{$id_customer}'";
    }
}else{
    returnError("Nhập id_customer");
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
            'id_bank' => $row['id_bank'],
            'customer_name' => htmlspecialchars_decode($row['customer_fullname']),
            'customer_phone' => htmlspecialchars_decode($row['customer_phone']),
            'request_syntax' => "KSENAPTIEN".$row['customer_phone']
        );

        array_push($customer_arr['data'], $customer_item);
    }
    reJson($customer_arr);
} else {
    returnSuccess("Không có khách hàng");
}