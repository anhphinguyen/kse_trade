<?php

if (isset($_REQUEST['id_customer']) && !empty($_REQUEST['id_customer'])) {
    $id_customer = $_REQUEST['id_customer'];
} else {
    returnError("Nhập id_customer");
}

$sql = "SELECT * FROM tbl_request_deposit
        WHERE id_customer = '$id_customer' AND request_type = '2'
         ORDER BY `tbl_request_deposit`.`request_time_completed` DESC";

$result_arr = array();
$result_arr['success'] = 'true';
$result_arr['data'] = array();
$result = db_qr($sql);
$nums = db_nums($result);

if ($nums > 0) {
    while ($row = db_assoc($result)) {

        $history_item = array(
            'request_value' => $row['request_value'],
            'request_time_completed' => date("d/m/Y - H:i", $row['request_time_completed']),
            'request_code' => $row['request_code']
        );

        array_push($result_arr['data'], $history_item);
    }
} 
reJson($result_arr);
