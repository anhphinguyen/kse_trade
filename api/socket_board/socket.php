<?php

if (isset($_REQUEST['time_now']) && !empty($_REQUEST['time_now'])) {
    $time_now = $_REQUEST['time_now'];
} else {
    returnError("Nhập time_now");
}

if (isset($_REQUEST['coordinate_xy']) && !empty($_REQUEST['coordinate_xy'])) {
    $coordinate_xy = $_REQUEST['coordinate_xy'];
} else {
    returnError("Nhập coordinate_xy");
}


// check exchange close 
$sql = "SELECT * FROM tbl_exchange_exchange WHERE exchange_close = '$time_now'";
$result = db_qr($sql);
$nums = db_nums($result);
if ($nums > 0) {
$time_tomorrow = $time_now + 86400;

    while ($row = db_assoc($result)) {
        $time_open = $row['exchange_open'];
        $time_close = $row['exchange_close'];
        $time_living = $row['exchange_period'];
        $id_exchange = $row['id'];
    }

    $sql = "SELECT exchange_close FROM tbl_exchange_temporary WHERE id_exchange = '$id_exchange'";
    $result = db_qr($sql);
    $nums = db_nums($result);
    if ($nums > 0) {
        while ($row = db_assoc($result)) {
            $id_temporary = $row['id_temporary'];
            if (date("d", $time_tomorrow) == date("d", $row['exchange_close'])) {
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

    $sql = "UPDATE tbl_exchange_exchange SET 
            exchange_open = '$time_open_tomorrow',
            exchange_close = '$time_close_tomorrow'
            WHERE id = '$id_exchange'
            ";
    if (db_qr($sql)) {
        returnSuccess("Tạo sàn cho ngày " . date("d/m/Y H:i:s",$time_open_tomorrow) . " Thành công");
    }
}


// check session open to get id_session
$sql = "SELECT *
        FROM tbl_exchange_period 
        WHERE period_open <= '$time_now' 
        AND period_close > '$time_now'";

$result = db_qr($sql);
$nums = db_nums($result);
if ($nums > 0) {
    while ($row = db_assoc($result)) {
        $id_session = $row['id'];
        $id_stock = $row['id_exchange'];
        $time_open = $row['period_open'];
        $session_time_close = strval((int)$row['period_close'] - 1);
        $day_session = date('d', $row['period_open']);
    }
} else {
    returnError("Chưa có phiên giao dịch này");
}

$sql = "UPDATE tbl_exchange_period 
        SET period_now = '$time_now' 
        WHERE period_open <= '$time_now' 
        AND period_close > '$time_now'";
db_qr($sql);

// add coordinate

$result_arr = array();
$result_arr['success'] = "true";
$result_arr['data'] = array();


$sql = "SELECT * FROM tbl_graph_info WHERE id_period = '$id_session'";

$result = db_qr($sql);
$nums = db_nums($result);
if ($nums > 0) {
    while ($row = db_assoc($result)) {
        $coordinate_xy_db = $row['x_y'];
        $coordinate_xy_arr = substr($coordinate_xy_db, 0, -1) . "," . $coordinate_xy . "]";

        $sql = "UPDATE tbl_graph_info SET
                x_y = '$coordinate_xy_arr'
                WHERE id_period = '$id_session'";

        if (db_qr($sql)) {
            // duration block
            $total_trade_up = get_total_money($id_session, 'up');
            $total_trade_down = get_total_money($id_session, 'down');

            if ($total_trade_up <= $total_trade_down) {
                $result_trade = "up";
                update_period_result($id_session,'up');
                trading_result_by_trading_type($id_session, 'up', 'win');
                trading_result_by_trading_type($id_session, 'down', 'lose');
                // Cộng tiền cho customer
                if ($time_now >= $session_time_close) {
                    customer_add_money($id_session, 'up');
                    demo_add_money($id_session, 'up');
                }
            } else {
                $result_trade = "down";
                trading_result_by_trading_type($id_session, 'up', 'lose');
                trading_result_by_trading_type($id_session, 'down', 'win');
                
                if ($time_now >= $session_time_close) {
                    customer_add_money($id_session, 'down');
                    demo_add_money($id_session, 'down');
                }
            }

            $sql = "SELECT * FROM tbl_exchange_period 
                    WHERE period_point_idle <= '$time_now' 
                    AND period_close > '$time_now'";

            $result = db_qr($sql);
            $nums = db_nums($result);
            if ($nums > 0) {
                $sql = "SELECT 
                        *
                        FROM tbl_graph_info 
                        WHERE id_period = '$id_session'";

                $result = db_qr($sql);
                $nums = db_nums($result);
                if ($nums > 0) {
                    while ($row = db_assoc($result)) {
                        $result_item = array(
                            'id_session' => $row['id_period'], 
                            'result_trade' => (isset($result_trade)&&!empty($result_trade))?$result_trade:"",
                            'status_trade' => 'block',
                            'coordinate_g' => $row['point_map']
                        );
                        array_push($result_arr['data'], $result_item);
                    }
                }
                reJson($result_arr);
            }
            $sql = "SELECT 
                        *
                        FROM tbl_graph_info 
                        WHERE id_period = '$id_session'";
            $result = db_qr($sql);
            $nums = db_nums($result);
            if ($nums > 0) {
                while ($row = db_assoc($result)) {
                    $result_item = array(
                        'id_session' => $row['id_period'],
                        'result_trade' => (isset($result_trade)&&!empty($result_trade))?$result_trade:"",
                        'status_trade' => 'trading',
                        'coordinate_g' => $row['point_map']
                    );
                    array_push($result_arr['data'], $result_item);
                }
            }
            reJson($result_arr);
        };
    }
}

$coordinate_xy_arr = "[" . $coordinate_xy . "]";
$sql = "INSERT INTO tbl_graph_info SET
        id_exchange = '$id_stock',
        id_period = '$id_session',
        x_y = '$coordinate_xy_arr',
        point_map = '$coordinate_xy'";

if (db_qr($sql)) {

    $sql = "SELECT * FROM tbl_exchange_period WHERE id = '$id_session' AND period_open = '$session_time_open'";

    $result = db_qr($sql);
    $nums = db_nums($result);
    if ($nums > 0) {
        $sql = "SELECT 
                        *
                        FROM tbl_graph_info 
                        WHERE id_period = '$id_session'";

        $result = db_qr($sql);
        $nums = db_nums($result);
        if ($nums > 0) {
            while ($row = db_assoc($result)) {
                $result_item = array(
                    'id_session' => $row['id_period'],
                    'result_trade' => (isset($result_trade)&&!empty($result_trade))?$result_trade:"",
                    'status_trade' => 'trading',
                    'coordinate_g' => $row['point_map']
                );
                array_push($result_arr['data'], $result_item);
            }
        }
        reJson($result_arr);
    }
} else {
    returnError("Lỗi truy vấn tọa độ");
}
