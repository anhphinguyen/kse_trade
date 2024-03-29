<?php

if (isset($_REQUEST['type_target'])) {
    if ($_REQUEST['type_target'] == '') {
        unset($_REQUEST['type_target']);
        returnError("type_target");
    } else {
        $type_manager = $_REQUEST['type_target'];
    }
} else {
    returnError("Nhập type_target");
}

switch ($type_manager) {
    case 'customer':
        $tbl_log = 'tbl_trading_log_record';
        $id = 'id_customer';
        break;
    case 'admin':
        $tbl_log = 'tbl_trading_log_record';
        break;
    case 'trainghiem':
        $tbl_log = 'tbl_customer_demo_log';
        $id = 'id_demo';
        break;
}

$sql = "SELECT * FROM $tbl_log
        WHERE 1=1";



if ($type_manager != 'admin') {
    if (isset($_REQUEST['id_customer'])) {
        if ($_REQUEST['id_customer'] == '') {
            unset($_REQUEST['id_customer']);
            returnError("type_manager");
        } else {
            $id_customer = $_REQUEST['id_customer'];
            $sql .= " AND `$tbl_log`.`$id` = '{$id_customer}'";

            $sql_get_total_win = "SELECT SUM(trading_bet) FROM $tbl_log WHERE trading_result = 'win' AND $id = $id_customer";
            $sql_get_total_lose = "SELECT SUM(trading_bet) FROM $tbl_log WHERE trading_result = 'lose' AND $id = $id_customer";
            
        }
    } else {
        returnError("id_customer");
    }
}


if (isset($_REQUEST['date_begin'])) {
    if ($_REQUEST['date_begin'] == '') {
        unset($_REQUEST['date_begin']);
    } else {
        $date_begin = strtotime($_REQUEST['date_begin'] . " 00:00:00");
        $sql .= " AND `trading_log` >= '{$date_begin}'";
        $sql_get_total_win .= " AND `trading_log` >= '{$date_begin}'";
        $sql_get_total_lose .= " AND `trading_log` >= '{$date_begin}'";

    }
} else {
    $three_month_ago = time() - 3 * 30 * 24 * 60 * 60; //7 776 000
    $sql .= " AND `trading_log` >= '" . $three_month_ago . "'";
    $sql_get_total_win .= " AND `trading_log` >= '" . $three_month_ago . "'";
    $sql_get_total_lose .= " AND `trading_log` >= '" . $three_month_ago . "'";

}

if (isset($_REQUEST['date_end'])) {
    if ($_REQUEST['date_end'] == '') {
        unset($_REQUEST['date_end']);
    } else {
        $date_end = strtotime($_REQUEST['date_end'] . " 23:59:59");
        $sql .= " AND `trading_log` <= '{$date_end}'";
        $sql_get_total_win .= " AND `trading_log` <= '{$date_end}'";
        $sql_get_total_lose .= " AND `trading_log` <= '{$date_end}'";

    }
} else {
    $month = time();
    $sql .= " AND `trading_log` <= '" . $month . "'";
    $sql_get_total_win .= " AND `trading_log` <= '" . $month . "'";
    $sql_get_total_lose .= " AND `trading_log` <= '" . $month . "'";
}

if (isset($_REQUEST['trading_result'])) {
    if ($_REQUEST['trading_result'] == '') {
        unset($_REQUEST['trading_result']);
    } else {
        $trading_result = $_REQUEST['trading_result'];
        $sql .= " AND `trading_result` = '{$trading_result}'";
        $sql_get_total_win .= " AND `trading_result` = '{$trading_result}'";
        $sql_get_total_lose .= " AND `trading_result` = '{$trading_result}'";
    }
}

$sql_total = "SELECT 
                ($sql_get_total_win) * (91/100) as total_win,
                ($sql_get_total_lose) as total_lose
                FROM $tbl_log 
                WHERE 1=1
                ";

            if (isset($id_customer)) {
                $sql_total .= " AND `$tbl_log`.`$id` = '{$id_customer}'";
            }

            if (isset($date_begin)) {
                $sql_total .= " AND `trading_log` >= '{$date_begin}'";
            }else{
                $sql_total .= " AND `trading_log` >= '" . $three_month_ago . "'";
            }

            if (isset($date_end)) {
                $sql_total .= " AND `trading_log` <= '{$date_end}'";
            }else{
                $sql_total .= " AND `trading_log` <= '" . $month . "'";
            }
            if (isset($trading_result )) {
                $sql_total .= " AND `trading_result` = '{$trading_result}'";
            }
            $sql_total .= " GROUP BY $id";

$customer_arr = array();

$total = count(db_fetch_array($sql));
$limit = 20;
$page = 1;

if (isset($_REQUEST['limit']) && !empty($_REQUEST['limit'])) {
    $limit = $_REQUEST['limit'];
}
if (isset($_REQUEST['page']) && !empty($_REQUEST['page'])) {
    $page = $_REQUEST['page'];
}


$total_page = ceil($total / $limit);
$start = ($page - 1) * $limit;
$sql .= " ORDER BY `$tbl_log`.`id` DESC LIMIT {$start},{$limit}";


$result_total = db_qr($sql_total);
if(db_nums($result_total) > 0){
    while($row_total = db_assoc($result_total)){
        $total_win = (!empty($row_total['total_win']))?(int)$row_total['total_win']:"0";
        $total_lose = (!empty($row_total['total_lose']))?$row_total['total_lose']:"0";
    }
}else{
    $total_win = '0';
    $total_lose = '0';
}


$customer_arr['success'] = 'true';

$customer_arr['total'] = strval($total);
$customer_arr['total_page'] = strval($total_page);
$customer_arr['total_win'] = strval($total_win);
$customer_arr['total_lose'] = $total_lose;
$customer_arr['limit'] = strval($limit);
$customer_arr['page'] = strval($page);
$customer_arr['data'] = array();
$result = db_qr($sql);
$nums = db_nums($result);

if ($nums > 0) {
    $total_win = 0;
    $total_lose = 0;
    while ($row = db_assoc($result)) {

        $customer_item = array(
            'id_trading' => $row['id'],
            'id_customer' => (isset($row['id_customer']) && !empty($row['id_customer'])) ? $row['id_customer'] : $row['id_demo'],
            'trading_log' => date("d/m/Y - H:i:s", $row['trading_log']),
            'trading_bet' => strval(($row['trading_bet'] * (($row['trading_result'] == 'win') ? $row['trading_percent'] : 100)) / 100),
            'trading_type' => $row['trading_type'],
            'trading_result' => (isset(($row['trading_result'])) && !empty(($row['trading_result']))) ? $row['trading_result'] : "",
            'trading_percent' => ($row['trading_result'] == 'win') ? $row['trading_percent'] : "",
        );

        array_push($customer_arr['data'], $customer_item);
    }
} 
reJson($customer_arr);
