<?php
$timestamp_current = time();
$date_current =  date("Y-m-d", time());
$date_begin_current = strtotime($date_current . " 00:00:00");

$type_target = 'customer';
if (isset($_REQUEST['type_target']) && !empty($_REQUEST['type_target'])) {
    $type_target = $_REQUEST['type_target'];
}

if (isset($_REQUEST['time_begin'])) {
    if ($_REQUEST['time_begin'] == '') {
        unset($_REQUEST['time_begin']);
    } else {
        $time_begin = $_REQUEST['time_begin'];
    }
}

if (isset($_REQUEST['time_end'])) {
    if ($_REQUEST['time_end'] == '') {
        unset($_REQUEST['time_end']);
    } else {
        $time_end = $_REQUEST['time_end'];
    }
}

if (isset($_REQUEST['date_begin'])) {
    if ($_REQUEST['date_begin'] == '') {
        unset($_REQUEST['date_begin']);
    } else {
        if (!empty($time_begin)) {
            $date_begin = strtotime($_REQUEST['date_begin'] . " " . $time_begin);
        } else {
            $date_begin = strtotime($_REQUEST['date_begin'] . " 00:00:00");
        }
    }
}

if (isset($_REQUEST['date_end'])) {
    if ($_REQUEST['date_end'] == '') {
        unset($_REQUEST['date_end']);
        $date_end = $timestamp_current;
    } else {
        if (!empty($time_end)) {
            $date_end = strtotime($_REQUEST['date_end'] . " " . $time_end);
        } else {
            $date_end = strtotime($_REQUEST['date_end'] . " 23:59:59");
        }
        if ($date_end > $timestamp_current) {
            $date_end = $timestamp_current;
        }
    }
} else {
    $date_end = $timestamp_current;
}

$sql = "SELECT 
        tbl_exchange_period.*,
        tbl_exchange_exchange.exchange_open
        FROM tbl_exchange_period
        LEFT JOIN tbl_exchange_exchange ON tbl_exchange_exchange.id = tbl_exchange_period.id_exchange
        WHERE 1=1 
        ";


// //////////////___TOTAL_EXCHANGE___////////////////////
$sql_exchange_total_win = "";
$sql_exchange_total_lose = "";
if ($type_target == 'admin') {
    $sql_exchange_total_win = "SELECT SUM(trading_bet)*(91/100) as total_win FROM tbl_trading_log WHERE trading_result = 'win'";

    $sql_exchange_total_lose = "SELECT SUM(trading_bet) as total_lose FROM tbl_trading_log WHERE trading_result = 'lose'";
}
// //////////////////////////////////////////////////////

if (!empty($date_begin)) {
    $sql .= " AND tbl_exchange_period.period_close > $date_begin";
    $sql .= " AND tbl_exchange_period.period_close <= $date_end";

    $sql_exchange_total_win .= " AND tbl_trading_log.trading_log >= $date_begin";
    $sql_exchange_total_win .= " AND tbl_trading_log.trading_log <= $date_end";

    $sql_exchange_total_lose .= " AND tbl_trading_log.trading_log >= $date_begin";
    $sql_exchange_total_lose .= " AND tbl_trading_log.trading_log <= $date_end";
} else {
    $sql .= " AND tbl_exchange_period.period_close > $date_begin_current";
    $sql .= " AND tbl_exchange_period.period_close <= $timestamp_current";


    $sql_exchange_total_win .= " AND tbl_trading_log.trading_log >= $date_begin_current";
    $sql_exchange_total_win .= " AND tbl_trading_log.trading_log <= $timestamp_current";

    $sql_exchange_total_lose .= " AND tbl_trading_log.trading_log >= $date_begin_current";
    $sql_exchange_total_lose .= " AND tbl_trading_log.trading_log <= $timestamp_current";
}

$exchange_total_win = 0;
$exchange_total_lose = 0;

if ($type_target == 'admin') {
    $result_exchange_total_win = db_qr($sql_exchange_total_win);
    $nums_exchange_total_win = db_nums($result_exchange_total_win);
    if ($nums_exchange_total_win > 0) {
        while ($row_exchange_total_win = db_assoc($result_exchange_total_win)) {
            $exchange_total_win = $row_exchange_total_win['total_win'];
        }
    }

    $result_exchange_total_lose = db_qr($sql_exchange_total_lose);
    $nums_exchange_total_lose = db_nums($result_exchange_total_lose);
    if ($nums_exchange_total_lose > 0) {
        while ($row_exchange_total_lose = db_assoc($result_exchange_total_lose)) {
            $exchange_total_lose = $row_exchange_total_lose['total_lose'];
        }
    }
}


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

$sql .= " ORDER BY `tbl_exchange_period`.`id` DESC LIMIT {$start},{$limit}";

$customer_arr['success'] = 'true';

$customer_arr['total'] = strval($total);
$customer_arr['total_page'] = strval($total_page);
$customer_arr['limit'] = strval($limit);
$customer_arr['page'] = strval($page);
$customer_arr['exchange_total_win'] = strval((int)$exchange_total_win);
$customer_arr['exchange_total_lose'] = strval($exchange_total_lose);
$customer_arr['data'] = array();
$result = db_qr($sql);
$nums = db_nums($result);

$exchange_total_win = 0;
$exchange_total_lose = 0;
if ($nums > 0) {
    while ($row = db_assoc($result)) {
        $customer_item = array(
            'id_period' => $row['id'],
            'id_exchange' => $row['id_exchange'],
            'exchange_open' => date("d/m/Y", $row['period_open']),
            'period_open' => date("H:i", $row['period_open']),
            'period_close' => date("H:i", $row['period_close']),
            'period_total_win' => '0',
            'period_total_lose' => '0',
            'period_result' => (isset($row['period_result']) && !empty($row['period_result'])) ? $row['period_result'] : "",
        );
        $id_session = $row['id'];

        $sql_get_total_win = "SELECT SUM(trading_bet) FROM tbl_trading_log WHERE trading_result = 'win' AND id_exchange_period = " . $id_session . "";
        $sql_get_total_lose = "SELECT SUM(trading_bet) FROM tbl_trading_log WHERE trading_result = 'lose' AND id_exchange_period = " . $id_session . "";

        $sql_total_bet = "SELECT 
                ($sql_get_total_win) * (91/100) as total_win,
                ($sql_get_total_lose) as total_lose
                FROM `tbl_trading_log`
                WHERE 1=1
                ";

        $sql_total_bet .= " GROUP BY id_customer";

        $result_get_bet_value = db_qr($sql_total_bet);
        $nums_result_get_bet_value = db_nums($result_get_bet_value);

        if ($nums_result_get_bet_value > 0) {
            while ($row_total = db_assoc($result_get_bet_value)) {

                $total_win = (!empty($row_total['total_win'])) ? (int)$row_total['total_win'] : "0";
                $total_lose = (!empty($row_total['total_lose'])) ? $row_total['total_lose'] : "0";
            }
            $customer_item['period_total_win'] = strval($total_win);
            $customer_item['period_total_lose'] = strval($total_lose);

            // $exchange_total_win += $total_win;
            // $exchange_total_lose += $total_lose;
        }

        array_push($customer_arr['data'], $customer_item);
    }


    reJson($customer_arr);
} else {
    returnError("Chưa có phiên");
}
