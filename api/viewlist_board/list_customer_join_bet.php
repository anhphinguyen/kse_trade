<?php

if (isset($_REQUEST['id_exchange'])) {
    if ($_REQUEST['id_exchange'] == '') {
        unset($_REQUEST['id_exchange']);
        returnError("Nhập id_exchange");
    } else {
        $id_exchange = $_REQUEST['id_exchange'];
    }
} else {
    returnError("Nhập id_exchange");
}

if (isset($_REQUEST['id_period'])) {
    if ($_REQUEST['id_period'] == '') {
        unset($_REQUEST['id_period']);
    } else {
        $id_peroid = $_REQUEST['id_period'];
    }
}
if (isset($id_peroid) && !empty($id_peroid)) {
    $id_session = $id_peroid;
} else {
    $time_present = time();

    $sql = "SELECT * FROM tbl_exchange_period 
        WHERE period_open <= '$time_present'
        AND period_close > '$time_present'
        AND id_exchange = '$id_exchange'";

    $result = db_qr($sql);
    $num = db_nums($result);
    if ($num > 0) {
        while ($row = db_assoc($result)) {
            $id_session = $row['id'];
        }
    }
}

$sql = "SELECT tbl_customer_customer.customer_fullname,
        tbl_customer_customer.customer_phone,
        
        tbl_trading_log.id_customer as customer_id,
        (SELECT SUM(trading_bet)  FROM tbl_trading_log WHERE id_customer = customer_id AND id_exchange_period = '$id_session') as bet_total,
        (SELECT SUM(trading_bet)  FROM tbl_trading_log WHERE trading_type = 'up' AND id_customer =customer_id AND id_exchange_period = '$id_session' ) as bet_up,
        (SELECT SUM(trading_bet) FROM  tbl_trading_log WHERE trading_type = 'down' AND id_customer =customer_id  AND id_exchange_period = '$id_session') as bet_down 

        FROM tbl_customer_customer
        LEFT JOIN tbl_trading_log ON tbl_customer_customer.id = tbl_trading_log.id_customer
        WHERE id_exchange_period = '$id_session'
        
        ";


$sql .= " GROUP BY tbl_trading_log.id_customer";
$customer_arr = array();

$total = count(db_fetch_array($sql));
$limit = 20;
$page = 1;

if(!empty($id_peroid)){
    $limit = 2000;
}

if (isset($_REQUEST['limit']) && !empty($_REQUEST['limit'])) {
    $limit = $_REQUEST['limit'];
}
if (isset($_REQUEST['page']) && !empty($_REQUEST['page'])) {
    $page = $_REQUEST['page'];
}


$total_page = ceil($total / $limit);
$start = ($page - 1) * $limit;
$sql .= " ORDER BY `tbl_customer_customer`.`id` DESC LIMIT {$start},{$limit}";

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
            'id_customer' => $row['customer_id'],
            'customer_fullname' => $row['customer_fullname'],
            'customer_phone' => $row['customer_phone'],
            'bet_total' => (!empty($row['bet_total'])) ? $row['bet_total'] : "0",
            'bet_up' => (!empty($row['bet_up'])) ? $row['bet_up'] : "0",
            'bet_down' => (!empty($row['bet_down'])) ? $row['bet_down'] : "0",
        );

        array_push($customer_arr['data'], $customer_item);
    }
}
reJson($customer_arr);
