<?php

$sql = "SELECT
            tbl_customer_customer.customer_fullname,
            tbl_request_deposit.*
            FROM tbl_request_deposit 
            LEFT JOIN tbl_customer_customer ON tbl_request_deposit.id_customer = tbl_customer_customer.id
            WHERE 1=1
        ";

if (isset($_REQUEST['id_request'])) {
    if ($_REQUEST['id_request'] == '') {
        unset($_REQUEST['id_request']);
        returnError("Nhập id_request");
    } else {
        $id_request = $_REQUEST['id_request'];
        $sql .= " AND `tbl_request_deposit`.`id` = '{$id_request}'";
    }
} else {
    returnError("Nhập id_request");
}

// echo $sql;
// exit();
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
            'customer_name' => htmlspecialchars_decode($row['customer_fullname']),
            'request_code' => $row['request_code'],
            'request_value' => $row['request_value'],
            'request_time_complete' => date("d/m/Y H:i",$row['request_time_completed']),
        );

        array_push($request_arr['data'], $request_item);
    }
    reJson($request_arr);
} else {
    returnError("Không tìm thấy yêu cầu");
}
