<?php
$day_today = time();
if(isset($_REQUEST['time_open']) && !empty($_REQUEST['time_open'])){
    $day_today = $_REQUEST['time_open'];
}

$sql = "SELECT * FROM tbl_exchange_exchange WHERE exchange_open <= '$day_today' AND exchange_close > '$day_today'
        ";

$result = db_qr($sql);
$nums = db_nums($result);
if ($nums > 0) {
    while ($row = db_assoc($result)) {
        $id_stock = $row['id'];
        $exchange_percent = $row['exchange_percent'];
        // $stock_quantity = $row['stock_quantity'];
    }
} else {
    errorCode('101', "Quý khách vui lòng đợi đến khi sàn mở lại");
    // errorCode('101', "Sàn giao dịch sẽ đóng cửa update cho ngày ".date("d/m/Y", time() + 86400).". Xin quý khách hàng chú ý !");
}

    $sql = "SELECT 
                    tbl_exchange_period.id as id_exchange_period,
                    tbl_exchange_period.id_exchange as id_exchange,
                    tbl_exchange_period.period_open as period_open,
                    tbl_exchange_period.period_now as period_now,
                    tbl_exchange_period.period_point_idle as period_point_idle,
                    tbl_exchange_period.period_close as period_close,

                    tbl_exchange_exchange.exchange_active as exchange_active,

                    tbl_graph_info.id as id_graph,
                    tbl_graph_info.x_y as coordinate_xy,
                    tbl_graph_info.point_map as point_map
                    FROM tbl_exchange_period 
                    LEFT JOIN tbl_graph_info ON tbl_graph_info.id_period = tbl_exchange_period.id
                    LEFT JOIN tbl_exchange_exchange ON tbl_exchange_exchange.id = tbl_exchange_period.id_exchange
                    WHERE 
                    tbl_exchange_period.period_open <= '$day_today' 
                    AND tbl_exchange_period.period_close > '$day_today'
                    ";
    // echo $sql;
    // exit();

    $result = db_qr($sql);
    $nums = db_nums($result);
    $result_arr = array();
    $result_arr['success'] = "true";
    $result_arr['data'] = array();

    if ($nums > 0) {
        while ($row = db_assoc($result)) {
            $result_item = array(
                'exchange_active' => $row['exchange_active'],
                'id_exchange' => $row['id_exchange'],
                'id_exchange_period' => $row['id_exchange_period'],
                'period_open' => date("H:i",$row['period_open']),
                'period_point_idle' => date("H:i",$row['period_point_idle']),
                'period_close' => date("H:i",$row['period_close']),//strval((int)$row['period_close'] - 1)

                'period_open_int' => $row['period_open'],
                'period_point_idle_int' => $row['period_point_idle'],
                'period_close_int' => $row['period_close'],//strval((int)$row['period_close'] - 1)

                'period_now' => (isset($row['period_now'])&&!empty($row['period_now']))?$row['period_now']:"",
                
                'exchange_percent' => (isset($exchange_percent)&&!empty($exchange_percent))?$exchange_percent:"",
                
                'time_bet' => strval((int)$row['period_point_idle'] - (int)$row['period_open']),
                'time_out' => strval((int)$row['period_close'] - (int)$row['period_point_idle']),
                'time_current' => (time() > $row['period_now'])?strval(time() - (int)$row['period_open']):strval((int)$row['period_now'] - (int)$row['period_open']),
                // 'time_current_int' => strval(time() - (int)$row['period_open']),
                'time_duration' => strval((int)$row['period_close'] - (int)$row['period_open']),
            );
            array_push($result_arr['data'], $result_item);
        }
        
    } else {
        returnError("Chưa có phiên giao dịch này");
    }
reJson($result_arr);
