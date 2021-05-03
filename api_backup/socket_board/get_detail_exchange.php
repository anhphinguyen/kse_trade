<?php
if (isset($_REQUEST['id_exchange'])) {
    if ($_REQUEST['id_exchange'] == '') {
        unset($_REQUEST['id_exchange']);
        returnError("Nhập id_exchange");
    } else {
        $id_exchange = $_REQUEST['id_exchange'];
    }
} else {
    $sql = "SELECT id FROM tbl_exchange_exchange";
    $result = db_qr($sql);
    if(db_nums($result) > 0){
        while($row = db_assoc($result)){
            $id_exchange = $row['id'];
        }
    }
}

$time_present = time();

$sql = "SELECT * FROM tbl_exchange_period 
        WHERE period_open <= '$time_present'
        AND period_close > '$time_present'";
$result = db_qr($sql);
$num = db_nums($result);
if ($num > 0) {
    while ($row = db_assoc($result)) {
        $id_session = $row['id'];
    }
}



$sql_people_up = "SELECT COUNT(id) as total_people_up FROM tbl_trading_log
        WHERE trading_type = 'up'
        AND id_exchange_period = '$id_session'
        ";
$result_people_up = db_qr($sql_people_up);
$nums_people_up = db_nums($result_people_up);
if ($nums_people_up > 0) {
    while ($row_people_up = db_assoc($result_people_up)) {
        $total_people_up = $row_people_up['total_people_up'];
    }
}

$sql_money_up = "SELECT SUM(trading_bet) as total_money_up FROM tbl_trading_log
        WHERE trading_type = 'up'
        AND id_exchange_period = '$id_session'
        ";
$result_money_up = db_qr($sql_money_up);
$nums_money_up = db_nums($result_money_up);
if ($nums_money_up > 0) {
    while ($row_money_up = db_assoc($result_money_up)) {
        $total_money_up = $row_money_up['total_money_up'];
    }
}

$sql_people_down = "SELECT COUNT(id) as total_people_down FROM tbl_trading_log
        WHERE trading_type = 'down'
        AND id_exchange_period = '$id_session'
        ";
$result_people_down = db_qr($sql_people_down);
$nums_people_down = db_nums($result_people_down);
if ($nums_people_down > 0) {
    while ($row_people_down = db_assoc($result_people_down)) {
        $total_people_down = $row_people_down['total_people_down'];
    }
}
$sql_money_down = "SELECT SUM(trading_bet) as total_money_down FROM tbl_trading_log
        WHERE trading_type = 'down'
        AND id_exchange_period = '$id_session'
        ";
$result_money_down = db_qr($sql_money_down);
$nums_money_down = db_nums($result_money_down);
if ($nums_money_down > 0) {
    while ($row_money_down = db_assoc($result_money_down)) {
        $total_money_down = $row_money_down['total_money_down'];
    }
}

$result_arr = array();
$sql = "SELECT * FROM tbl_exchange_exchange WHERE id = '$id_exchange'";
$result = db_qr($sql);
$nums = db_nums($result);
if ($nums > 0) {
    $result_arr['success'] = 'true';
    $result_arr['data'] = array();
    while ($row = db_assoc($result)) {

        $exchange_quantity = get_exchange_quantity($row['id']);
        $result_item = array(
            'id_exchange' => $row['id'],
            'exchange_name' => $row['exchange_name'],
            'exchange_open' => date("H:i", $row['exchange_open']),
            'exchange_close' => date("H:i", $row['exchange_close']),
            'exchange_quantity' => strval($exchange_quantity),
            'exchange_period' => strval($row['exchange_period'] / 60),
            'exchange_updated_by' => (isset($row['exchange_updated_by']) && !empty($row['exchange_updated_by'])) ? $row['exchange_updated_by'] : "0",
            'total_people_up' => (isset($total_people_up) && !empty($total_people_up)) ? $total_people_up : "0",
            'total_people_down' => (isset($total_people_down) && !empty($total_people_down)) ? $total_people_down : "0",
            'total_money_up' => (isset($total_money_up) && !empty($total_money_up)) ? $total_money_up : "0",
            'total_money_down' => (isset($total_money_down) && !empty($total_money_down)) ? $total_money_down : "0",
        );
        array_push($result_arr['data'], $result_item);
    }
}
reJson($result_arr);
