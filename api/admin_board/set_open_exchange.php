<?php

if(isset($_REQUEST['exchange_open']) && !empty($_REQUEST['exchange_open'])){
    $exchange_open = $_REQUEST['exchange_open'];
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

if(isset($_REQUEST['exchange_living']) && !empty($_REQUEST['exchange_living'])){
    $exchange_living = $_REQUEST['exchange_living'];
}else{
    returnError("Nhập exchange_living");
}

if(isset($_REQUEST['exchange_close']) && !empty($_REQUEST['exchange_close'])){
    $exchange_close = $_REQUEST['exchange_close'];
}else{
    returnError("Nhập exchange_close");
}

$delta_time = $exchange_close - $exchange_open;

$quantity = $delta_time / $exchange_living;

$time_start = $exchange_open;

$sql = "INSERT INTO tbl_exchange_exchange SET
        exchange_open = '$exchange_open',
        exchange_close = '$exchange_close',
        exchange_period = '$exchange_living'
        ";
if(db_qr($sql)){
    $id_stock = mysqli_insert_id($conn);

    for($i = 1; $i <= $quantity; $i++){
        $time_session = $time_start + $exchange_living;
        $time_break = $time_session - 60;  // mặc đinh thời gian không cho phép đặt cược là 60s
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
    }
}
