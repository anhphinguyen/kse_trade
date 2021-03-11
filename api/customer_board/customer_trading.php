<?php

if (isset($_REQUEST['id_customer']) && !empty($_REQUEST['id_customer'])) {
    $id_customer = $_REQUEST['id_customer'];
} else {
    returnError("Nhập id_customer");
}


$time = time();
$sql = "SELECT * FROM tbl_exchange_period WHERE period_open <= '$time' AND period_close > '$time'";
$result = db_qr($sql);
$nums = db_nums($result);
if ($nums > 0) {
    while ($row = db_assoc($result)) {
        $id_exchange_period = $row['id'];
    }
} else {
    returnError("Chưa có phiên giao dịch");
}

$trading_log = time();

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
        if ($trading_bet > $row['customer_wallet_bet']) {
            returnError("Số tiền trong tài khoản của bạn không đủ để trade. Xin vui lòng nạp tiền để có thể đầu tư");
        }
        $sub_money = $row['customer_wallet_bet'] - $trading_bet;
        $sql = "UPDATE tbl_customer_customer SET customer_wallet_bet = '$sub_money' WHERE id = '$id_customer'";
        db_qr($sql);
    }
}

$sql = "SELECT * FROM tbl_exchange_period WHERE id = '$id_exchange_period'";
$result = db_qr($sql);
$nums = db_nums($result);
if ($nums > 0) {
    while ($row = db_assoc($result)) {
        $sql = "SELECT exchange_percent FROM tbl_exchange_exchange WHERE id = '" . $row['id_exchange'] . "'";
        $result = db_qr($sql);
        $nums = db_nums($result);
        if ($nums > 0) {
            while ($row = db_assoc($result)) {
                $exchange_percent = $row['exchange_percent'];
            }
        }
    }
}

$sql = "INSERT INTO tbl_trading_log SET
            id_customer = '$id_customer',
            id_exchange_period = '$id_exchange_period',
            trading_bet = '$trading_bet',
            trading_log = '$trading_log',
            trading_percent = '$exchange_percent',
            trading_type = '$trading_type'";
if (db_qr($sql)) {
    returnSuccess("Bạn đã đặt " . $trading_type);
} else {
    returnError("Lỗi truy vấn");
}
