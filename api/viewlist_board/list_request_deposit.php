<?php

$sql = "SELECT 
            tbl_request_deposit.*,
            tbl_customer_customer.customer_fullname
            FROM tbl_request_deposit
            LEFT JOIN tbl_customer_customer
            ON tbl_customer_customer.id = tbl_request_deposit.id_customer
            WHERE 1=1";


if (isset($_REQUEST['date_begin'])) {
    if ($_REQUEST['date_begin'] == '') {
        unset($_REQUEST['date_begin']);
    } else {
        $date_begin = $_REQUEST['date_begin'];
        $sql .= " AND `request_time_completed` >= '{$date_begin}"." 00:00:00'";
    }
}else{
    $three_month_ago = time() - 3*30*24*60*60; //7 776 000

    $month = date("Y-m", $three_month_ago);
    $sql .= " AND `request_time_completed` >= '".$month."-1 00:00:00'";
    
}

if (isset($_REQUEST['date_end'])) {
    if ($_REQUEST['date_end'] == '') {
        unset($_REQUEST['date_end']);
    } else {
        $date_end = $_REQUEST['date_end'];
        $sql .= " AND `request_time_completed` <= '{$date_end}"." 23:59:59'";
    }
}else{
    $month = date("Y-m", time());
    $sql .= " AND `request_time_completed` <= '".$month."-31 23:59:59'";
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

if (empty($error)) {
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
                'customer_fullname' => htmlspecialchars_decode($row['customer_fullname']),
                'request_code' =>$row['request_code'],
                'request_value' =>$row['request_value'],
                'request_time_completed' =>$row['request_time_completed'],
            );

            array_push($customer_arr['data'], $customer_item);
        }
        reJson($customer_arr);
    } else {
        returnSuccess("Không tìm thấy yêu cầu");
    }
}
