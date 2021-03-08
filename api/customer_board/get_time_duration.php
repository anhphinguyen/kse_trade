<?php
$day_today = time();

$sql = "SELECT * FROM tbl_exchange_exchange WHERE exchange_open <= '$day_today' AND exchange_close >= '$day_today'";

$result = db_qr($sql);
$nums = db_nums($result);
if ($nums > 0) {
    while ($row = db_assoc($result)) {
        $id_stock = $row['id'];
        // $stock_quantity = $row['stock_quantity'];
    }
} else {
    returnError("Hôm nay không có sàn giao dịch");
}

for ($i = 0; $i < $stock_quantity; $i++) {

    $sql = "SELECT 
                    tbl_exchange_period.id as id_exchange_period,
                    tbl_exchange_period.id_exchange as id_exchange,
                    tbl_exchange_period.period_open as period_open,
                    tbl_exchange_period.period_now as period_now,

                    tbl_graph_info.id as id_graph,
                    tbl_graph_info.x_y as coordinate_xy,
                    tbl_graph_info.point_map as point_map
                    FROM tbl_exchange_period 
                    LEFT JOIN tbl_graph_info ON tbl_graph_info.id_exchange_period = tbl_exchange_period.id
                    WHERE 
                    tbl_exchange_period.period_open <= '$day_today'
                    ";
    // echo $sql;
    // exit();

    $result = db_qr($sql);
    $nums = db_nums($result);
    $result_arr = array();

    if ($nums > 0) {
        while ($row = db_assoc($result)) {
            $result_item = array(
                'id_graph' => $row['id_graph'],
                'id_exchange' => $row['id_exchange'],
                'id_exchange_period' => $row['id_exchange_period'],
                'period_open' => $row['period_open'],
                'period_point_idle' => $row['period_point_idle'],
                'period_close' => strval((int)$row['period_close'] - 1),
                'period_now' => $row['period_now'],
                'time_duration' => strval((int)$row['period_now'] - (int)$row['period_open']),
            );
            array_push($result_arr, $result_item);
        }
        
    } else {
        returnError("Chưa có phiên giao dịch này");
    }
}
reJson($result_arr);
