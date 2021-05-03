<?php

$day_today = time();
$sql = "SELECT * FROM tbl_exchange_exchange WHERE exchange_open > '1615449600'";

$result = db_qr($sql);
$nums = db_nums($result);

if ($nums > 0) {
    while ($row = db_assoc($result)) {
        echo json_encode(array(
            'success' => 'false',
            'error_code' => '101',
            'massage' => 'Qúy khách xin vui lòng đợi cho đến khi sàn mở lại ! ',
            // 'time_remain' =>  time()
        ));
    }
}else{
    returnSuccess("Đã mở sàn");
}