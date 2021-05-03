<?php
if (isset($_REQUEST['type_customer'])) {
    if ($_REQUEST['type_customer'] == '') {
        unset($_REQUEST['type_customer']);
        returnError("Nhập type_customer");
    } else {
        $type_customer = $_REQUEST['type_customer'];
    }
} else {
    returnError("Nhập type_customer");
}

if (isset($_REQUEST['id_customer']) && !empty($_REQUEST['id_customer'])) {
    $id_customer = $_REQUEST['id_customer'];
} else {
    returnError("Nhập id_customer");
}

$exchange_active = 'Y';
$sql_check_exchange_active = "SELECT * FROM tbl_exchange_exchange WHERE 1=1";
$result_check_exchange_active = db_qr($sql_check_exchange_active);
$num_result_check_exchange_active = db_nums($result_check_exchange_active);
if ($num_result_check_exchange_active > 0) {
    while ($row_check_exchange_active = db_assoc($result_check_exchange_active)) {
        $exchange_active = $row_check_exchange_active['exchange_active'];
    }
}
if($exchange_active == 'N'){
    returnError("Sàn giao dịch đang bảo trì, vui lòng quay lại sau!");
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
    if($trading_bet < 30000){
        returnError("Số tiền đặt cược tối thiểu là 30.000, xin vui lòng đặt thêm!");
    }
    if (strpos($trading_bet, '.') !== false)
                    $trading_bet = str_replace(".", "", $trading_bet);

                if (strpos($trading_bet, ',') !== false)
                    $trading_bet = str_replace(",", "", $trading_bet);
} else {
    returnError("Nhập trading_bet");
}

if (isset($_REQUEST['trading_type']) && !empty($_REQUEST['trading_type'])) {
    $trading_type = $_REQUEST['trading_type'];
} else {
    returnError("Nhập trading_type");
}

switch($type_customer){
    case 'customer':{
        $sql = "SELECT * FROM tbl_customer_customer WHERE id = '$id_customer'";
        break;
    }
    case 'trainghiem':{
        $sql = "SELECT * FROM tbl_customer_demo WHERE id = '$id_customer'";
        break;
    }
}

// $sql = "SELECT * FROM tbl_customer_customer WHERE id = '$id_customer'";
$result = db_qr($sql);
$nums = db_nums($result);
if ($nums > 0) {
    while ($row = db_assoc($result)) {
        $wallet_bet = (isset($row['customer_wallet_bet']) && !empty($row['customer_wallet_bet']))?$row['customer_wallet_bet']:$row['demo_wallet_bet'];
        if ($trading_bet > $wallet_bet) {
            returnError("Số tiền trong tài khoản của bạn không đủ để trade. Xin vui lòng nạp tiền để có thể đầu tư");
        }
        $sub_money = $wallet_bet - $trading_bet;

        if($type_customer == 'trainghiem'){
            $sql = "UPDATE tbl_customer_demo SET demo_wallet_bet = '$sub_money' WHERE id = '$id_customer'";
        }else{
            $sql = "UPDATE tbl_customer_customer SET customer_wallet_bet = '$sub_money' WHERE id = '$id_customer'";
        }
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

switch($type_customer){
    case 'customer':{
        $sql = "INSERT INTO tbl_trading_log SET
            id_customer = '$id_customer'
            ";
        break;
    }
    case 'trainghiem':{
        $sql = "INSERT INTO tbl_customer_demo_log SET
            id_demo = '$id_customer'
            ";
        break;
    }
}

$sql .= ", id_exchange_period = '$id_exchange_period',
            trading_bet = '$trading_bet',
            trading_log = '$trading_log',
            trading_percent = '$exchange_percent',
            trading_type = '$trading_type'";

if (db_qr($sql)) {
    returnSuccess("Bạn đã đặt " . $trading_type);
} else {
    returnError("Lỗi truy vấn");
}
