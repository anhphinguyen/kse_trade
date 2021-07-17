<?php

$sql = "SELECT account_code FROM tbl_account_account WHERE 1=1 ";
if (isset($_REQUEST['id_account'])) {
    if ($_REQUEST['id_account'] == '') {
        unset($_REQUEST['id_account']);
        returnError("Nhập id_account");
    } else {
        $id_account = $_REQUEST['id_account'];
        $sql .= " AND id = '$id_account'";
    }
}

if (isset($_REQUEST['type_sort'])) {
    if ($_REQUEST['type_sort'] == '') {
        unset($_REQUEST['type_sort']);
    } else {
        $type_sort = $_REQUEST['type_sort'];
    }
}
if (isset($id_account) && !empty($id_account)) {
    $result = db_qr($sql);
    $nums = db_nums($result);
    if ($nums > 0) {
        while ($row = db_assoc($result)) {
            $customer_introduce = $row['account_code'];
        }
    }
}


$sql = "SELECT 
            tbl_customer_customer.customer_fullname,
            tbl_customer_customer.customer_phone,
            tbl_customer_customer.customer_introduce,
            tbl_customer_customer.id as customer_id,
            tbl_customer_customer.customer_registered as customer_registered,

            -- tbl_trading_log.trading_type as trading_type,
            (SELECT SUM(tbl_request_deposit.request_value) FROM tbl_request_deposit WHERE tbl_request_deposit.id_customer = customer_id AND tbl_request_deposit.request_type != '2') as total_money_deposit,
            (SELECT SUM(tbl_request_payment.request_value) FROM tbl_request_payment WHERE tbl_request_payment.id_customer = customer_id AND  tbl_request_payment.request_status = '3') as total_money_payment
                        
            FROM  tbl_customer_customer
            
            -- LEFT JOIN tbl_trading_log
            -- ON tbl_customer_customer.id = tbl_trading_log.id_customer
            
            LEFT JOIN tbl_account_account 
            ON tbl_customer_customer.customer_introduce = tbl_account_account.account_code
            
            WHERE  1=1
        ";

$sql_total = "SELECT COUNT(id) as total FROM tbl_customer_customer WHERE 1=1 ";

if (isset($id_account) && !empty($id_account)) {
    $sql .= " AND tbl_customer_customer.customer_introduce = '$customer_introduce'";
    $sql_total .= " AND tbl_customer_customer.customer_introduce = '$customer_introduce'";
}


if (isset($_REQUEST['filter'])) {
    if ($_REQUEST['filter'] == '') {
        unset($_REQUEST['filter']);
    } else {
        $filter = htmlspecialchars($_REQUEST['filter']);
        $sql .= " AND ( tbl_customer_customer.customer_fullname LIKE '%{$filter}%'";
        $sql .= " OR tbl_customer_customer.customer_phone LIKE '%{$filter}%' )";
        $sql_total .= " AND ( tbl_customer_customer.customer_fullname LIKE '%{$filter}%'";
        $sql_total .= " OR tbl_customer_customer.customer_phone LIKE '%{$filter}%' )";
    }
}

$sql .= " GROUP BY tbl_customer_customer.id";
$result_arr = array();

$result_total = db_qr($sql_total);
if (db_nums($result_total) > 0) {
    while ($row_total = db_assoc($result_total)) {
        $total = $row_total['total'];
    }
}

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

$sql .= " ORDER BY tbl_customer_customer.id DESC LIMIT {$start},{$limit}";


$result_arr['total_page'] = strval($total_page);
$result_arr['limit'] = strval($limit);
$result_arr['page'] = strval($page);
$result_arr['success'] = "true";
$result_arr['total'] = strval($total);
$result_arr['data'] = array();

$result = db_qr($sql);
$nums = db_nums($result);
if ($nums > 0) {
    while ($row = db_assoc($result)) {
        $result_item = array(
            'id_customer' => (isset($row['customer_id']) && !empty($row['customer_id'])) ? $row['customer_id'] : "",
            'customer_name' => $row['customer_fullname'],
            'customer_phone' => $row['customer_phone'],
            'customer_registered' => $row['customer_registered'],
            'total_money_deposit' => (!empty($row['total_money_deposit'])) ? $row['total_money_deposit'] : "0",
            'total_money_payment' => (!empty($row['total_money_payment'])) ? $row['total_money_payment'] : "0",
            'trading_log' => (!empty($row['trading_log'])) ? date("d/m/Y", $row['trading_log']) : "",
        );
        array_push($result_arr['data'], $result_item);
    }
    reJson($result_arr);
} else {
    returnError("Danh sách trống");
}
