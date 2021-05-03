<?php

if (isset($_REQUEST['session_time_break']) && !empty($_REQUEST['session_time_break'])) {
    $session_time_break = $_REQUEST['session_time_break'];
} else {
    $session_time_break = time();
}
$result_arr = array();
$result_arr['success'] = "true";
$result_arr['data'] = array();
///////////////////////////////////////////////////////////////////////////
$sql = "SELECT id,period_open,period_point_idle,period_close FROM tbl_exchange_period 
        WHERE period_open <= '$session_time_break'
        AND period_close > '$session_time_break'";

$result = db_qr($sql);
$num = db_nums($result);

if ($num > 0) {
    while ($row = db_assoc($result)) {
        $id_session = $row['id'];
        // $exchange_percent = $row['exchange_percent'];
        $time_open = $row['period_open'];
        $time_block = $row['period_point_idle'];
        $time_close = $row['period_close'];
        $session_time_close = strval((int)$row['period_close'] - 1);
    }
} else {
    returnError('Chưa có phiên được tạo');
}

$sql_get_coordinate_g = "SELECT point_map FROM tbl_graph_info
                            WHERE id_period = '$id_session'";

$result_get_coordinate_g = db_qr($sql_get_coordinate_g);
$nums_get_coordinate_g = db_nums($result_get_coordinate_g);

if ($nums_get_coordinate_g > 0) {
    while ($row_get_coordinate_g = db_assoc($result_get_coordinate_g)) {
        $coordinate_g = $row_get_coordinate_g['point_map'];
    }
}
////////////////////////////////////////////////////////////////////////////
// $sql = "SELECT id FROM tbl_exchange_period 
//         WHERE period_point_idle <= '$session_time_break'
//         AND period_close > '$session_time_break'";

// $result = db_qr($sql);
// $nums = db_nums($result);

if ($session_time_break >= $time_block && $session_time_break < $time_close ) {
    $result_item = array(
        'status_trade' => "block",
        'time_open' => $time_open,
        'time_block' => $time_block,
        'time_close' => $time_close,
        'coordinate_g' => isset($coordinate_g) ? $coordinate_g : "null",
    );
    array_push($result_arr['data'], $result_item);
    reJson($result_arr);
}

// $sql = "SELECT * FROM tbl_exchange_period 
//         WHERE period_open <= '$session_time_break'
//         AND period_point_idle > '$session_time_break'";
// $result = db_qr($sql);
// $nums = db_nums($result);

if ($session_time_break >= $time_open && $session_time_break < $time_block) {
    $result_item = array(
        'status_trade' => "trading",
        'time_open' => $time_open,
        'time_block' => $time_block,
        'time_close' => $time_close,
        'coordinate_g' => isset($coordinate_g) ? $coordinate_g : "null",
    );
    array_push($result_arr['data'], $result_item);
    reJson($result_arr);
}else{
    returnError("Lỗi truy vấn trading");
}
