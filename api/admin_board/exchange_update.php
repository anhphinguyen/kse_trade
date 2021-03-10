<?php
if(isset($_REQUEST['id_account']) && !empty($_REQUEST['id_account'])){
    $id_account = $_REQUEST['id_account'];
}else{
    returnError("Nhập id_account");
}

if(isset($_REQUEST['id_exchange']) && !empty($_REQUEST['id_exchange'])){
    $id_exchange = $_REQUEST['id_exchange'];
}else{
    returnError("Nhập id_exchange");
}

if(isset($_REQUEST['exchange_open']) && !empty($_REQUEST['exchange_open'])){
    $exchange_open = $_REQUEST['exchange_open'];
    if(date("d", time()) == date("d", $exchange_open)){
        returnError("Bạn chỉ được cập nhật thông tin sàn cho ngày hôm sau");
    }
}else{
    returnError("Nhập exchange_open");
}

if(isset($_REQUEST['exchange_period']) && !empty($_REQUEST['exchange_period'])){
    $exchange_period = $_REQUEST['exchange_period'];
}else{
    returnError("Nhập exchange_period");
}

if(isset($_REQUEST['exchange_close']) && !empty($_REQUEST['exchange_close'])){
    $exchange_close = $_REQUEST['exchange_close'];
}else{
    returnError("Nhập exchange_close");
}

$delta_time = $exchange_close - $exchange_open;

$quantity = $delta_time / $exchange_period;

$time_start = $exchange_open;

$sql = "INSERT INTO tbl_exchange_temporary SET
        id_exchange = '$id_exchange',
        exchange_open = '$exchange_open',
        exchange_close = '$exchange_close',
        exchange_updated_by = '$id_account',
        exchange_period = '$exchange_period'
        ";
if(db_qr($sql)){
    if(isset($success)){
        returnSuccess("Cập nhật thông tin sàn thành công");
    }
}else{
    returnError("Lỗi truy vấn");
}
