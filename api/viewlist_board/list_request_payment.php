<?php

$sql = "SELECT 
            tbl_request_payment.*,
            tbl_customer_customer.customer_fullname
            FROM tbl_request_payment
            LEFT JOIN tbl_customer_customer
            ON tbl_customer_customer.id = tbl_request_payment.id_customer
            WHERE 1=1";
// id
// id_customer
// request_created
// request_code
// request_value
// request_completed
// request_status
if (isset($_REQUEST['filter_status'])) {
    if ($_REQUEST['filter_status'] == '') {
        unset($_REQUEST['filter_status']);
    } else {
        $filter_status = htmlspecialchars($_REQUEST['filter_status']);
        $sql .= " AND request_status = '$filter_status'";
    }
}

if (isset($_REQUEST['date_begin'])) {
    if ($_REQUEST['date_begin'] == '') {
        unset($_REQUEST['date_begin']);
    } else {
        $date_begin = $_REQUEST['date_begin'];
        $sql .= " AND `request_created` >= '{$date_begin}" . " 00:00:00'";
    }
} else {
    $three_month_ago = time() - 3 * 30 * 24 * 60 * 60; //7 776 000

    $month = date("Y-m", $three_month_ago);
    $sql .= " AND `request_created` >= '" . $month . "-1 00:00:00'";
}

if (isset($_REQUEST['date_end'])) {
    if ($_REQUEST['date_end'] == '') {
        unset($_REQUEST['date_end']);
    } else {
        $date_end = $_REQUEST['date_end'];
        $sql .= " AND `request_created` <= '{$date_end}" . " 23:59:59'";
    }
} else {
    $month = date("Y-m", time());
    $sql .= " AND `request_created` <= '" . $month . "-31 23:59:59'";
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
            'id_customer' => $row['id_customer'],
            'customer_name' => htmlspecialchars_decode($row['customer_fullname']),
            'request_status' => $row['request_status'],
            'request_code' => $row['request_code'],
            'request_value' => $row['request_value'],
            'request_created' => $row['request_created'],
        );

        array_push($customer_arr['data'], $customer_item);
    }
    reJson($customer_arr);
} else {
    returnSuccess("Không tìm thấy yêu cầu");
}
