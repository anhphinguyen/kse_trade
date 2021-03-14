<?php
if (isset($_REQUEST['type_manager'])) {
    if ($_REQUEST['type_manager'] == '') {
        unset($_REQUEST['type_manager']);
        returnError("type_manager");
    } else {
        $type_manager = $_REQUEST['type_manager'];
    }
}else{
    returnError("type_manager");
}

$sql = "SELECT 
            tbl_request_payment.*,
            tbl_customer_customer.customer_fullname
            FROM tbl_request_payment
            LEFT JOIN tbl_customer_customer
            ON tbl_customer_customer.id = tbl_request_payment.id_customer
            WHERE 1=1";

if($type_manager == 'customer'){
    if (isset($_REQUEST['id_customer'])) {
        if ($_REQUEST['id_customer'] == '') {
            unset($_REQUEST['id_customer']);
            returnError("type_manager");
        } else {
            $id_customer = $_REQUEST['id_customer'];
            $sql .= " AND `tbl_request_payment`.`id_customer` = '{$id_customer}'";
        }
    }else{
        returnError("id_customer");
    }
}

if (isset($_REQUEST['filter'])) {
    if ($_REQUEST['filter'] == '') {
        unset($_REQUEST['filter']);
    } else {
        $filter = htmlspecialchars($_REQUEST['filter']);
        $sql .= " AND ( tbl_customer_customer.customer_code LIKE '%{$filter}%'";
        $sql .= " OR tbl_customer_customer.customer_fullname LIKE '%{$filter}%'";
        $sql .= " OR tbl_request_payment.request_code LIKE '%{$filter}%'";
        $sql .= " OR tbl_customer_customer.customer_phone LIKE '%{$filter}%' )";
    }
}

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
        $date_begin = strtotime($_REQUEST['date_begin']. " 00:00:00");
        $sql .= " AND `request_created` >= '{$date_begin}'";
    }
} else {
    $three_month_ago = time() - 3 * 30 * 24 * 60 * 60; //7 776 000
    $sql .= " AND `request_created` >= '" . $three_month_ago . "'";
}

if (isset($_REQUEST['date_end'])) {
    if ($_REQUEST['date_end'] == '') {
        unset($_REQUEST['date_end']);
    } else {
        $date_end = strtotime($_REQUEST['date_end']. " 23:59:59");
        $sql .= " AND `request_created` <= '{$date_end}'";
    }
} else {
    $month = time();
    $sql .= " AND `request_created` <= '" . $month . "'";
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
$sql .= " ORDER BY `tbl_request_payment`.`id` DESC LIMIT {$start},{$limit}";

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
            'id_request' => $row['id'],
            'id_customer' => $row['id_customer'],
            'customer_name' => htmlspecialchars_decode($row['customer_fullname']),
            'request_status' => $row['request_status'],
            'request_code' => $row['request_code'],
            'request_value' => $row['request_value'],
            'request_created' => date("d/m/Y H:i",$row['request_created']),
            'type'=>'payment'
        );

        array_push($customer_arr['data'], $customer_item);
    }
    reJson($customer_arr);
} else {
    returnError("Không tìm thấy yêu cầu");
}
