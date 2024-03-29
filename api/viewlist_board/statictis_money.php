<?php
if (isset($_REQUEST['type_manager'])) {
    if ($_REQUEST['type_manager'] == '') {
        unset($_REQUEST['type_manager']);
    }
}

if (!isset($_REQUEST['type_manager'])) {
    returnError("type_manager is missing!");
}

$typeManager = $_REQUEST['type_manager'];
switch ($typeManager) {
    case 'request_payment':
        $tbl_request = 'tbl_request_payment';
        break;

    case 'request_deposit':
        $tbl_request = 'tbl_request_deposit';

        break;

    default:
        returnError("type_manager is not accept!");
        break;
}
$time_complete = ($typeManager == 'request_payment') ? 'request_completed' : 'request_time_completed';
$sql_count = "SELECT 
                COUNT($tbl_request.id) 
                FROM $tbl_request 
                WHERE $tbl_request.id_customer = tbl_customer_customer.id
                ";
$sql_sum = "SELECT 
            SUM($tbl_request.request_value) 
            FROM $tbl_request 
            WHERE $tbl_request.id_customer = tbl_customer_customer.id
            ";

if (isset($_REQUEST['date_begin'])) {
    if ($_REQUEST['date_begin'] == '') {
        unset($_REQUEST['date_begin']);
    } else {
        $date_begin = strtotime($_REQUEST['date_begin'] . " 00:00:00");
        $sql_count .= " AND `$time_complete` >= '{$date_begin}'";
        $sql_sum .= " AND `$time_complete` >= '{$date_begin}'";
    }
} else {
    $month = date('Y-m',time());
    $three_month_ago = strtotime($month . "-01 00:00:00"); //7 776 000
    $sql_count .= " AND `$time_complete` >= '" . $three_month_ago . "'";
    $sql_sum .= " AND `$time_complete` >= '" . $three_month_ago . "'";
}

if (isset($_REQUEST['date_end'])) {
    if ($_REQUEST['date_end'] == '') {
        unset($_REQUEST['date_end']);
    } else {
        $date_end = strtotime($_REQUEST['date_end'] . " 23:59:59");
        $sql_count .= " AND `$time_complete` <= '{$date_end}'";
        $sql_sum .= " AND `$time_complete` <= '{$date_end}'";
    }
} else {
    $month = time();
    $sql_count .= " AND `$time_complete` <= '" . $month . "'";
    $sql_sum .= " AND `$time_complete` <= '" . $month . "'";
}

$sql = "SELECT 
        tbl_customer_customer.id as id_customer,
        tbl_customer_customer.customer_fullname as customer_fullname,
        tbl_customer_customer.customer_phone as customer_phone,

        ($sql_count) as total,
        ($sql_sum) as total_money,

        tbl_account_account.id as id_account,
        tbl_account_account.account_code as account_code
        FROM tbl_customer_customer
        LEFT JOIN $tbl_request ON $tbl_request.id_customer = tbl_customer_customer.id
        LEFT JOIN tbl_account_account ON tbl_account_account.account_code = tbl_customer_customer.customer_introduce
        WHERE tbl_customer_customer.customer_virtual = 'N'
       ";
// $sql = "SELECT 
//         COUNT($tbl_request.id) as total,
//         tbl_customer_customer.id as id_customer,

//         (SELECT SUM(request_value) FROM $tbl_request WHERE $tbl_request.id_customer = id_customer) as total_money, 
//         $tbl_request.id_customer,
//         tbl_account_account.id as id_account,
//         tbl_account_account.account_code as account_code,
//         tbl_customer_customer.customer_fullname as customer_fullname,
//         tbl_customer_customer.customer_phone as customer_phone
//         FROM $tbl_request 
//         LEFT JOIN tbl_customer_customer ON $tbl_request.id_customer = tbl_customer_customer.id
//         LEFT JOIN tbl_account_account ON tbl_account_account.account_code = tbl_customer_customer.customer_introduce
//         WHERE tbl_customer_customer.customer_virtual = 'N'
//         ";

if($typeManager == 'request_deposit'){
    $sql .= " AND tbl_request_deposit.request_type != '2' ";
}


if (isset($_REQUEST['id_account']) && !empty($_REQUEST['id_account'])) {
    $id_account = $_REQUEST['id_account'];
    $sql .= " AND tbl_account_account.id = '$id_account' ";
}

// $time_complete = ($typeManager == 'request_payment') ? 'request_completed' : 'request_time_completed';

if (isset($_REQUEST['date_begin'])) {
    if ($_REQUEST['date_begin'] == '') {
        unset($_REQUEST['date_begin']);
    } else {
        $date_begin = strtotime($_REQUEST['date_begin'] . " 00:00:00");
        $sql .= " AND $tbl_request.$time_complete >= '{$date_begin}'";
    }
} else {
    $month = date('Y-m',time());
    $three_month_ago = strtotime($month . "-01 00:00:00"); //7 776 000
    $sql .= " AND $tbl_request.$time_complete >= '" . $three_month_ago . "'";
}

if (isset($_REQUEST['date_end'])) {
    if ($_REQUEST['date_end'] == '') {
        unset($_REQUEST['date_end']);
    } else {
        $date_end = strtotime($_REQUEST['date_end'] . " 23:59:59");
        $sql .= " AND $tbl_request.$time_complete <= '{$date_end}'";
    }
} else {
    $month = time();
    $sql .= " AND $tbl_request.$time_complete <= '" . $month . "'";
}

// switch ($typeManager) {
//     case 'request_payment':
//         $sql .= " GROUP BY tbl_request_payment.id_customer
//                  ";
//         break;

//     case 'request_deposit':
//         $sql .= " GROUP BY tbl_request_deposit.id_customer
//                  ";
//         break;

//     default:
//         returnError("type_manager is not accept!");
//         break;
// }
$sql .= "GROUP BY id_customer ORDER BY total_money DESC";


$result_arr = array();
$result_arr['success'] = 'true';
$result_arr['data'] = array();
$result = db_qr($sql);
$nums = db_nums($result);
$total_temp = 0;
if ($nums > 0) {
    while ($row = db_assoc($result)) {
        if((int)$row['total_money'] != 0){
            $result_item = array(
                'id_customer' => $row['id_customer'],
                'customer_fullname' => $row['customer_fullname'],
                'customer_phone' => $row['customer_phone'],
                'id_account' => (!empty($row['id_account']))?$row['id_account']:"",
                'account_code' => (!empty($row['account_code']))?$row['account_code']:"",
                'total_money' => $row['total_money'],
                'total' => $row['total'],
            );
            $total_bonus = 0;
            
            $total_temp += ($row['total_money'] - $total_bonus);
            array_push($result_arr['data'], $result_item);
        }
    }
} 

$result_arr['total'] = strval($total_temp);
reJson($result_arr);
