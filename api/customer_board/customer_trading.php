<?php

if (isset($_REQUEST['id_customer']) && !empty($_REQUEST['id_customer'])) {
    $id_customer = $_REQUEST['id_customer'];
} else {
    returnError("Nhập id_customer");
}

if (isset($_REQUEST['id_exchange_period']) && !empty($_REQUEST['id_exchange_period'])) {
    $id_exchange_period = $_REQUEST['id_exchange_period'];
} else {
    returnError("Nhập id_exchange_period");
}

if (isset($_REQUEST['trading_log']) && !empty($_REQUEST['play_trading_logtime'])) {
    $trading_log = $_REQUEST['trading_log'];
} else {
    $trading_log = time();
}

if (isset($_REQUEST['trading_bet']) && !empty($_REQUEST['trading_bet'])) {
    $trading_bet = $_REQUEST['trading_bet'];
} else {
    returnError("Nhập trading_bet");
}

if (isset($_REQUEST['trading_type']) && !empty($_REQUEST['trading_type'])) {
    $trading_type = $_REQUEST['trading_type'];
} else {
    returnError("Nhập trading_type");
}

$sql = "SELECT * FROM tbl_customer_customer WHERE id = '$id_customer'";
$result = db_qr($sql);
$nums = db_nums($result);
if ($nums > 0) {
    while ($row = db_assoc($result)) {
        $sub_money = $row['customer_wallet_bet'] - $trading_bet;
        $sql = "UPDATE tbl_customer_customer SET customer_wallet_bet = '$sub_money' WHERE id = '$id_customer'";
        db_qr($sql);
    }
}



$sql = "INSERT INTO tbl_trading_log SET
            id_customer = '$id_customer',
            id_exchange_period = '$id_exchange_period',
            trading_bet = '$trading_bet',
            trading_log = '$trading_log',
            trading_type = '$trading_type'";
if (db_qr($sql)) {
    // $id_insert = mysqli_insert_id($conn);
    // $sql = "SELECT * FROM tbl_trading_log WHERE id = '$id_insert'";
    // $result = db_qr($sql);
    // if (db_nums($result)) {
    //     while ($row = db_assoc($result)) {
    //         $result_arr = array(
    //             'id_playing' => $row['id'],
    //             'id_user' => $row['id_user'],
    //             'id_session' => $row['id_session'],
    //             'bet_money' => $row['play_bet_money'],
    //             'play_status_trade' => $row['play_status_trade'],
    //             'play_result' => (!empty($row['play_status_bet'])) ? $row['play_status_bet'] : "",
    //         );
    //     }
    //     reJson($result_arr);
    // }

    returnSuccess("Bạn đã đặt ".$trading_type);
}