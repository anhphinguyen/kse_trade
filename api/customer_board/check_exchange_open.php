<?php

$day_today = time();
$sql = "SELECT * FROM tbl_exchange_exchange WHERE exchange_open <= '$day_today' AND exchange_close >= '$day_today'";

$result = db_qr($sql);
$nums = db_nums($result);

if ($nums > 0) {
    while ($row = db_assoc($result)) {
        echo json_encode(array(
            'success' => 'true',
            'error_code' => '101',
            'massage' => 'Quy khách xin vui lòng đợi cho đến khi sàn mở lại ! ',
            'time_remain' =>  strval((int)$row['exchange_open'] - time())
        ));
    }
}else{
    returnError("Chưa có thông tin giao dịch cho ngày hôm sau");
}