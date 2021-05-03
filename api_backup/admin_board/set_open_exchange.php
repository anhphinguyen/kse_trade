<?php

if(isset($_REQUEST['exchange_open']) && !empty($_REQUEST['exchange_open'])){
    $exchange_open = strtotime($_REQUEST['exchange_open']);
    $sql = "SELECT exchange_open FROM tbl_exchange_exchange";
    $result = db_qr($sql);
    $num = db_nums($result);
    if($num > 0){
        while($row = db_assoc($result)){
            if(date('d', $row['exchange_open']) == date('d', $exchange_open)){
                returnError("Đã tạo sàn cho ngày này");
            }
        }
    }
}else{
    returnError("Nhập exchange_open");
}

if(isset($_REQUEST['exchange_name']) && !empty($_REQUEST['exchange_name'])){
    $exchange_name = $_REQUEST['exchange_name'];
}else{
    returnError("Nhập exchange_name");
}

if(isset($_REQUEST['exchange_period']) && !empty($_REQUEST['exchange_period'])){
    $exchange_period = $_REQUEST['exchange_period'];
}else{
    returnError("Nhập exchange_period");
}

if(isset($_REQUEST['exchange_close']) && !empty($_REQUEST['exchange_close'])){
    $exchange_close = strtotime($_REQUEST['exchange_close']);
}else{
    returnError("Nhập exchange_close");
}

if($exchange_close < $exchange_open){
	returnError("Thời gian đóng sàn không thể thấp hơn thời gian mở sàn!");
}

$delta_time = $exchange_close - $exchange_open;

$quantity = $delta_time / $exchange_period;
$time_start = $exchange_open;

$sql = "INSERT INTO tbl_exchange_exchange SET
        exchange_open = '$exchange_open',
        exchange_name = '$exchange_name',
        exchange_close = '$exchange_close',
        exchange_period = '$exchange_period'
        ";
        
if(db_qr($sql)){
    $id_stock = mysqli_insert_id($conn);
    $sql = "SELECT * FROM tbl_exchange_exchange WHERE id = '$id_stock'";
    $result = db_qr($sql);
    $nums = db_nums($result);
    if($nums > 0){
        while($row = db_assoc($result)){
            $exchange_idle = $row['exchange_idle'];
        }
    }else{
        returnError("Lỗi truy vấn exchange_idle");
    }

    for($i = 1; $i <= $quantity; $i++){
        $time_session = $time_start + $exchange_period;
        $time_break = $time_session - $exchange_idle;  // mặc đinh thời gian không cho phép đặt cược là 60s
        $sql = "INSERT INTO tbl_exchange_period SET 
                id_exchange = '$id_stock',
                period_open = '$time_start', 
                period_point_idle = '$time_break',
                period_close = '$time_session'";
                
        if(db_qr($sql)){
            $success = true;
        };
        $time_start = $time_session;
    }
    if(isset($success)){
        returnSuccess("Tạo sàn thành công");
    }else{
        returnError("Lỗi truy vấn Phiên");
    }
}else{
    returnError("Lỗi truy vấn Sàn");
}
