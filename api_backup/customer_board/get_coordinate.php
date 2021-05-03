<?php
$day_today = time();

$sql = "SELECT * FROM tbl_exchange_exchange WHERE exchange_open <= '$day_today' AND exchange_close >= '$day_today'";

$result = db_qr($sql);
$nums = db_nums($result);
if ($nums > 0) {
    while ($row = db_assoc($result)) {
        $id_stock = $row['id'];
    }
} else {
    returnError("Hôm nay không có sàn giao dịch");
}


$sql = "SELECT 
                    tbl_exchange_period.id as id_exchange_period,
                    tbl_exchange_period.id_exchange as id_exchange,

                    tbl_graph_info.id as id_graph,
                    tbl_graph_info.x_y as coordinate_xy,
                    tbl_graph_info.point_map as point_map
                    FROM  tbl_graph_info
                    LEFT JOIN tbl_exchange_period ON tbl_graph_info.id_period = tbl_exchange_period.id
                    WHERE 
                    tbl_exchange_period.period_open <= '$day_today'
                    AND tbl_exchange_period.period_close > '$day_today'
                    ";

$result = db_qr($sql);
$nums = db_nums($result);
$result_arr = array();
$result_arr['success'] = "true";
$result_arr['data'] = array();

if ($nums > 0) {
    while ($row = db_assoc($result)) {
        $result_item = array(
            'id_graph' => $row['id_graph'],
            'id_exchange' => $row['id_exchange'],
            'id_exchange_period' => $row['id_exchange_period'],
            'coordinate_xy' => $row['coordinate_xy'],
            'coordinate_g' => $row['point_map'],
        );
        array_push($result_arr['data'], $result_item);
    }
} else {
    returnError("Chưa có phiên giao dịch này");
}
reJson($result_arr);
