<?php
if (isset($_REQUEST['session_time_open']) && !empty($_REQUEST['session_time_open'])) {
    $session_time_open = $_REQUEST['session_time_open'];
} else {
    returnError("Nhập session_time_open");
}



$sql = "SELECT * FROM tbl_exchange_period 
        WHERE period_open <= '$session_time_open' 
        AND period_close > '$session_time_open'";

$result = db_qr($sql);
$nums = db_nums($result);
if ($nums > 0) {
    while ($row = db_assoc($result)) {
        $id_session = $row['id'];
        $id_stock = $row['id_exchange'];
        $time_open = $row['period_open'];
        $day_session = date('d', $row['period_open']);
    }
} else {
    returnError("Chưa có phiên giao dịch này");
}

if (isset($_REQUEST['coordinate_xy']) && !empty($_REQUEST['coordinate_xy'])) {
    $coordinate_xy = $_REQUEST['coordinate_xy'];
} else {
    returnError("Nhập coordinate_xy");
}

if (isset($_REQUEST['time_present']) && !empty($_REQUEST['time_present'])) {
    $time_present = $_REQUEST['time_present'];
} else {
    returnError("Nhập time_present");
}

$sql = "UPDATE tbl_exchange_period 
        SET period_now = '$time_present' 
        WHERE period_open <= '$time_present' 
        AND period_close > '$time_present'";


db_qr($sql);


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
        // echo $sql;
        // exit();
        $result_arr = array();
        $result_arr['success'] = "true";
        $result_arr['data'] = array();

        if (db_qr($sql)) {
            $sql = "SELECT * FROM tbl_exchange_period 
                    WHERE period_point_idle <= '$session_time_open' 
                    AND period_close > '$session_time_open'";

            $result = db_qr($sql);
            $nums = db_nums($result);
            if ($nums > 0) {

                $sql = "SELECT 
                        tbl_exchange_period.period_open,
                        tbl_exchange_period.period_point_idle,
                        tbl_exchange_period.period_close,
                        tbl_graph_info.*
                        FROM tbl_graph_info 
                        LEFT JOIN tbl_exchange_period ON tbl_exchange_period.id = tbl_graph_info.id_period
                        WHERE id_period = '$id_session'";

                $result = db_qr($sql);
                $nums = db_nums($result);
                if ($nums > 0) {
                    while ($row = db_assoc($result)) {
                        $result_item = array(
                            'id_session' => $row['id_period'], ///////
                            'status_trade' => 'block',
                            'period_open' => $row['period_open'],
                            'period_point_idle' => $row['period_point_idle'],
                            'period_close' => $row['period_close'],
                            'coordinate_g' => $row['point_map']
                        );
                        array_push($result_arr['data'], $result_item);
                    }
                }
                reJson($result_arr);
            }
            $sql = "SELECT 
                        tbl_exchange_period.period_open,
                        tbl_exchange_period.period_point_idle,
                        tbl_exchange_period.period_close,
                        tbl_graph_info.*
                        FROM tbl_graph_info 
                        LEFT JOIN tbl_exchange_period ON tbl_exchange_period.id = tbl_graph_info.id_period
                        WHERE id_period = '$id_session'";
            // echo $sql;
            // exit();
            $result = db_qr($sql);
            $nums = db_nums($result);
            if ($nums > 0) {
                while ($row = db_assoc($result)) {
                    $result_item = array(
                        'id_session' => $row['id_period'],
                        'status_trade' => 'trading',
                        'period_open' => $row['period_open'],
                        'period_point_idle' => $row['period_point_idle'],
                        'period_close' => $row['period_close'],
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
    // $id_insert = mysqli_insert_id($conn);
    $result_arr = array();
    $result_arr['success'] = "true";
    $result_arr['data'] = array();

    $sql = "SELECT * FROM tbl_exchange_period WHERE id = '$id_session' AND period_open = '$session_time_open'";

    $result = db_qr($sql);
    $nums = db_nums($result);
    if ($nums > 0) {
        $sql = "SELECT 
                        tbl_exchange_period.period_open,
                        tbl_exchange_period.period_point_idle,
                        tbl_exchange_period.period_close,
                        tbl_graph_info.*
                        FROM tbl_graph_info 
                        LEFT JOIN tbl_exchange_period ON tbl_exchange_period.id = tbl_graph_info.id_period
                        WHERE id_period = '$id_session'";

        $result = db_qr($sql);
        $nums = db_nums($result);
        if ($nums > 0) {
            while ($row = db_assoc($result)) {
                $result_item = array(
                    'id_session' => $row['id_period'],
                    'status_trade' => 'trading',
                    'period_open' => $row['period_open'],
                    'period_point_idle' => $row['period_point_idle'],
                    'period_close' => $row['period_close'],
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
