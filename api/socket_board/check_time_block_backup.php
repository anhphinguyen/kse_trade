<?php

if (isset($_REQUEST['session_time_break']) && !empty($_REQUEST['session_time_break'])) {
    $session_time_break = $_REQUEST['session_time_break'];
} else {
    $session_time_break = time();
}
$result_arr = array();
$result_arr['success'] = "true";
$result_arr['data'] = array();

$sql = "SELECT * FROM tbl_exchange_period 
        WHERE period_point_idle <= '$session_time_break'
        AND period_close > '$session_time_break'";
        

$result = db_qr($sql);
$nums = db_nums($result);

if ($nums > 0) {
    $result_item = array(
        'status_trade' => "block"
    );
    array_push($result_arr['data'], $result_item);
    reJson($result_arr);
}

$sql = "SELECT * FROM tbl_exchange_period 
        WHERE period_open <= '$session_time_break'
        AND period_point_idle > '$session_time_break'";
$result = db_qr($sql);
$nums = db_nums($result);

if ($nums > 0) {
    $result_item = array(
        'status_trade' => "trading"
    );
    array_push($result_arr['data'], $result_item);
    reJson($result_arr);
}
