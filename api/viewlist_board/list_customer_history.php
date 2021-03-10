<?php 

$sql = "SELECT * FROM tbl_trading_log
        WHERE 1=1";

if (isset($_REQUEST['id_customer'])) {
    if ($_REQUEST['id_customer'] == '') {
        unset($_REQUEST['id_customer']);
        returnError("Nhập id_customer");
    } else {
        $id_customer = $_REQUEST['id_customer'];
        $sql .= " AND `id_customer` = '{$id_customer}'";
    }
}else{
    returnError("Nhập id_customer");
}


if (isset($_REQUEST['date_begin'])) {
    if ($_REQUEST['date_begin'] == '') {
        unset($_REQUEST['date_begin']);
    } else {
        $date_begin = strtotime($_REQUEST['date_begin']. " 00:00:00");
        $sql .= " AND `trading_log` >= '{$date_begin}'";
    }
} else {
    $three_month_ago = time() - 3 * 30 * 24 * 60 * 60; //7 776 000
    $sql .= " AND `trading_log` >= '" . $three_month_ago . "'";
}

if (isset($_REQUEST['date_end'])) {
    if ($_REQUEST['date_end'] == '') {
        unset($_REQUEST['date_end']);
    } else {
        $date_end = strtotime($_REQUEST['date_end']. " 23:59:59");
        $sql .= " AND `trading_log` <= '{$date_end}'";
    }
} else {
    $month = time();
    $sql .= " AND `trading_log` <= '" . $month . "'";
}

if (isset($_REQUEST['trading_result'])) {
    if ($_REQUEST['trading_result'] == '') {
        unset($_REQUEST['trading_result']);
    } else {
        $trading_result = $_REQUEST['trading_result'];
        $sql .= " AND `trading_result` = '{$trading_result}'";
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
$sql .= " ORDER BY `tbl_trading_log`.`id` DESC LIMIT {$start},{$limit}";

$customer_arr['success'] = 'true';

$customer_arr['total'] = strval($total);
$customer_arr['total_page'] = strval($total_page);
$customer_arr['limit'] = strval($limit);
$customer_arr['page'] = strval($page);
$customer_arr['data'] = array();
$result = db_qr($sql);
$nums = db_nums($result);

if ($nums > 0) {
    while ($row = db_assoc($result)) {
        $customer_item = array(
            'id_trading' => $row['id'],
            'id_customer' => $row['id_customer'],
            'trading_log' => date("d/m/Y - H:i", $row['trading_log']),
            'trading_bet' => $row['trading_bet'],
            'trading_type' => $row['trading_type'],
            'trading_result' => $row['trading_result'],
            'trading_percent' => ($row['trading_result'] === 'win' )?$row['trading_percent']:"",
        );

        array_push($customer_arr['data'], $customer_item);
    }
    reJson($customer_arr);
} else {
    returnSuccess("Lịch sử trống, hãy làm nên lịch sử nhé");
}

?>