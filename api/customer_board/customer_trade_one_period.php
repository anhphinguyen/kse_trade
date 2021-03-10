<?php

$sql = "SELECT 
        tbl_trading_log.*,
        tbl_exchange_period.period_open,
        tbl_exchange_period.period_result
        FROM tbl_trading_log
        LEFT JOIN tbl_exchange_period ON tbl_exchange_period.id = tbl_trading_log.id_exchange_period
        WHERE 1=1
        ";

if (isset($_REQUEST['id_customer'])) {
    if ($_REQUEST['id_customer'] == '') {
        unset($_REQUEST['id_customer']);
        returnError("Nhập id_customer");
    } else {
        $id_customer = $_REQUEST['id_customer'];
        $sql .= " AND `tbl_trading_log`.`id_customer` = '{$id_customer}'";
    }
}else{
    returnError("Nhập id_customer");
}

$time = time();
$sql = "SELECT * FROM tbl_exchange_period WHERE period_open <= '$time' AND period_close > '$time'";
$result = db_qr($sql);
$nums = db_nums($result);
if ($nums > 0) {
    while ($row = db_assoc($result)) {
        $id_exchange_period = $row['id'];
    }
}else{
    returnError("Chưa có phiên giao dịch");
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
$sql .= " ORDER BY `tbl_trading_log`.`id` ASC LIMIT {$start},{$limit}";

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
            'id_exchange_period' => $row['id_exchange_period'],
            // 'period_open' => $row['period_open'],
            'trading_log' => $row['trading_log'],
            'trading_bet' => $row['trading_bet'],
            'trading_type' => $row['trading_type'],
            'period_result' => (!empty($row['period_result']))?$row['period_result']:"",
            
        );

        array_push($customer_arr['data'], $customer_item);
    }
    reJson($customer_arr);
} else {
    returnError("Không có thông tin thao tác");
}