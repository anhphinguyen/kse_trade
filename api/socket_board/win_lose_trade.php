<?php

if (isset($_REQUEST['time_break']) && !empty($_REQUEST['time_break'])) {
    $time_break = $_REQUEST['time_break'];
} else {
    $time_break = time();
}

$sql = "SELECT * FROM tbl_exchange_period 
        WHERE period_open <= '$time_break'
        AND period_point_idle > '$time_break'";

$result = db_qr($sql);
$nums = db_nums($result);
if ($nums > 0) {
    returnError("Chưa đến thời gian dừng đặt cược");
}


$sql = "SELECT * FROM tbl_exchange_period 
        WHERE period_open <= '$time_break'
        AND period_close > '$time_break'";

$result = db_qr($sql);
$num = db_nums($result);

if ($num > 0) {
    while ($row = db_assoc($result)) {
        $id_session = $row['id'];
        // $exchange_percent = $row['exchange_percent'];
        $session_time_close = strval((int)$row['period_close'] - 1);
    }
} else {
    returnError('Chưa có phiên được tạo');
}

$sql_trade_up = "SELECT SUM(trading_bet) as total_money_up FROM tbl_trading_log 
                 WHERE id_exchange_period = '$id_session' 
                 AND trading_type = 'up'";

$result_trade_up = db_qr($sql_trade_up);
$nums_trade_up = db_nums($result_trade_up);

if ($nums_trade_up > 0) {
    while ($row_up = db_assoc($result_trade_up)) {
        $total_trade_up = $row_up['total_money_up'];
    }
}

$sql_trade_down = "SELECT SUM(trading_bet) as total_money_down FROM tbl_trading_log 
                 WHERE id_exchange_period = '$id_session' 
                 AND trading_type = 'down'";

$result_trade_down = db_qr($sql_trade_down);
$nums_trade_down = db_nums($result_trade_down);

if ($nums_trade_down > 0) {
    while ($row_down = db_assoc($result_trade_down)) {
        $total_trade_down = $row_down['total_money_down'];
    }
}

if ($total_trade_up < $total_trade_down) {
    $result_trade = "up";
    // Cộng tiền cho customer
    if ($time_break >= $session_time_close) {
        result_up($id_session);
    }
} elseif ($total_trade_up > $total_trade_down) {
    $result_trade = "down";
    // Cộng tiền cho customer
    if ($time_break >= $session_time_close) {
        result_down($id_session);
    }
} else {
    $result_trade = array('up', 'down');
    $result_random = array_rand($result_trade);
    if ($time_break >= $session_time_close) {
        if ($result_trade[$result_random] == 'up') {
            result_up($id_session);
        } else {
            result_down($id_session);
        }
    }
}

$sql_session = "SELECT id FROM tbl_exchange_period 
                WHERE period_open <= '$time_break'
                AND period_close >= '$time_break'";
$result_session = db_qr($sql_session);
$nums_session = db_nums($result_session);

if ($nums_session > 0) {
    while ($row_session = db_assoc($result_session)) {
        $id_session = $row_session['id'];
    }
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
$result_arr = array();
$result_arr['success'] = "true";
$result_arr['data'] = array();
$result_item = array(
    'result_trade' => $result_trade,
    'coordinate_g' => isset($coordinate_g) ? $coordinate_g : "null",
    'time_close' => strval($session_time_close - 1)
);
array_push($result_arr['data'], $result_item);


reJson($result_arr);
