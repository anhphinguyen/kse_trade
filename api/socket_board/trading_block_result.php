<?php
if (isset($_REQUEST['time_now']) && !empty($_REQUEST['time_now'])) {
    $session_time_break = $_REQUEST['time_now'];
} else {
    $session_time_break = time();
}

$sql = "SELECT * FROM tbl_exchange_period 
        WHERE period_open <= '$session_time_break'
        AND period_close > '$session_time_break'";

$result = db_qr($sql);
$num = db_nums($result);

if ($num > 0) {
    while ($row = db_assoc($result)) {
        $id_session = $row['id'];
        $session_time_close = strval((int)$row['period_close'] - 1);
    }
} else {
    returnError('Chưa có phiên được tạo');
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

    // WIN LOSE 
    $total_trade_up = get_total_money($id_session, 'up');
    $total_trade_down = get_total_money($id_session, 'down');

    if ($total_trade_up <= $total_trade_down) {
        $result_trade = "up";
        update_period_result($id_session,'up');
        trading_result_by_trading_type($id_session, 'up', 'win');
        trading_result_by_trading_type($id_session, 'down', 'lose');
        // Cộng tiền cho customer
        if ($time_now >= $session_time_close) {
            customer_add_money($id_session, 'up');
            demo_add_money($id_session, 'up');
        }
    } else {
        $result_trade = "down";
        trading_result_by_trading_type($id_session, 'up', 'lose');
        trading_result_by_trading_type($id_session, 'down', 'win');
        
        if ($time_now >= $session_time_close) {
            customer_add_money($id_session, 'down');
            demo_add_money($id_session, 'down');
        }
    }

    $result_item = array(
        'status_trade' => "block",
        'result_trade' => (isset($result_trade)&&!empty($result_trade))?$result_trade:"",
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
        'status_trade' => "trading",
        'result_trade' => (isset($result_trade)&&!empty($result_trade))?$result_trade:"",
    );
    array_push($result_arr['data'], $result_item);
    reJson($result_arr);
}