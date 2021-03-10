<?php

$sql = "SELECT 
        tbl_exchange_period.*,
        tbl_exchange_exchange.exchange_open
        FROM tbl_exchange_period
        LEFT JOIN tbl_exchange_exchange ON tbl_exchange_exchange.id = tbl_exchange_period.id_exchange
        WHERE 1=1
        ";

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
$customer_arr['data'] = array();
$result = db_qr($sql);
$nums = db_nums($result);


if ($nums > 0) {
    while ($row = db_assoc($result)) {
        $customer_item = array(
            'id_period' => $row['id'],
            'id_exchange' => $row['id_exchange'],
            'exchange_open' => $row['exchange_open'],
            'period_open' => $row['period_open'],
            'period_close' => $row['period_close'],
            'period_result' => $row['period_result'],
        );
        array_push($customer_arr['data'], $customer_item);
    }
    reJson($customer_arr);
} else {
    returnError("Chưa có phiên");
}
