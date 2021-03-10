
<?php

if (isset($_REQUEST['stock_time_close']) && !empty($_REQUEST['stock_time_close'])) {
    $stock_time_close = $_REQUEST['stock_time_close'];
} else {
    returnError("Giao dịch hôm nay chưa kết thúc, chưa thể tạo sàn cho hôm sau");
}

$stock_time_close_tomorrow = $stock_time_close + 86400;

$sql = "SELECT * FROM tbl_exchange_exchange WHERE exchange_close = '$stock_time_close'";
$result = db_qr($sql);
$nums = db_nums($result);
if ($nums > 0) {
    while ($row = db_assoc($result)) {
        $time_open = $row['exchange_open'];
        $time_close = $row['exchange_close'];
        $time_living = $row['exchange_period'];
        $id_exchange = $row['id'];
        // $quantity = ($time_close - $time_open)/$time_living;
    }


    $sql = "SELECT exchange_close FROM tbl_exchange_temporary WHERE id_exchange = '$id_exchange'";
    $result = db_qr($sql);
    $nums = db_nums($result);
    if ($nums > 0) {
        while ($row = db_assoc($result)) {
            $id_temporary = $row['id_temporary'];
            if (date("d", $stock_time_close_tomorrow) == date("d", $row['exchange_close'])) {
                $sql = "UPDATE tbl_exchange_exchange SET 
                    exchange_open = '" . $row['exchange_open'] . "',
                    exchange_close = '" . $row['exchange_close'] . "',
                    exchange_period = '" . $row['exchange_period'] . "',
                    exchange_idle = '" . $row['exchange_idle'] . "',
                    exchange_updated_by = '" . $row['exchange_updated_by'] . "'
                    WHERE id = '$id_exchange'
                    ";
                if (db_qr($sql)) {
                    returnSuccess("Sàn tiếp theo sẽ mở vào lúc " . date("d/m/Y H:i:s",$time_open_tomorrow) . " Thành công");
                }
            }
        }
    }

    $time_open_tomorrow = $time_open + 86400;
    $time_close_tomorrow = $time_close + 86400;
    $quantity = ($time_close_tomorrow - $time_open_tomorrow) / $time_living;

    $sql = "UPDATE tbl_exchange_exchange SET 
            exchange_open = '$time_open_tomorrow',
            exchange_close = '$time_close_tomorrow'
            WHERE id = '$id_exchange'
            ";
    if (db_qr($sql)) {
        returnSuccess("Tạo sàn cho ngày " . date("d/m/Y H:i:s",$time_open_tomorrow) . " Thành công");
    }
    // echo $time_open_tomorrow." Tạo sàn cho ngày hôm sau thành công";
    // exit();
}else{
    returnError("Chưa kết thúc sàn");
}
