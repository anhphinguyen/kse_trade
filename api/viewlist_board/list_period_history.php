<?php

$day_today = time();
$sql = "SELECT * FROM tbl_exchange_exchange WHERE exchange_open <= '$day_today' AND exchange_close >= '$day_today'";

$result = db_qr($sql);
$nums = db_nums($result);
if ($nums > 0) {
    while ($row = db_assoc($result)) {
        $id_stock = $row['id'];
        $exchange_open = $row['exchange_open'];
    }
} else {
    returnError("Hôm nay không có sàn giao dịch");
}

$sql = "SELECT * FROM tbl_exchange_period WHERE id_exchange = '$id_stock'";
$stock_quantity = count(db_fetch_array($sql));
for ($i = 0; $i < $stock_quantity; $i++) {

    $sql = "SELECT * FROM tbl_exchange_period 
            WHERE period_open <= '$day_today'
            AND id_exchange = '$id_stock'
            ";

    $result = db_qr($sql);
    $nums = db_nums($result);
    $result_arr = array();
    $result_arr['success'] = "true";
    $result_arr['data'] = array();

    if ($nums > 0) {
        while ($row = db_assoc($result)) {
            if(!empty($row['period_result'])){
                $result_item = array(
                    'id' => $row['id'],
                    'period_open' => $row['period_open'],
                    'period_close' => $row['period_close'],
                    'period_result' => $row['period_result'],
                );
                array_push($result_arr['data'], $result_item);
            }
        }
    }
}
reJson($result_arr);