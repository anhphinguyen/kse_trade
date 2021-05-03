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
$sql = "SELECT 
        COUNT($tbl_request.id) as total,
        SUM(request_value) as total_money, 
        $tbl_request.id_customer,
        tbl_account_account.id as id_account,
        tbl_account_account.account_code as account_code,
        tbl_customer_customer.customer_fullname as customer_fullname,
        tbl_customer_customer.customer_phone as customer_phone
        FROM $tbl_request 
        LEFT JOIN tbl_customer_customer ON $tbl_request.id_customer = tbl_customer_customer.id
        LEFT JOIN tbl_account_account ON tbl_account_account.account_code = tbl_customer_customer.customer_introduce
        WHERE tbl_customer_customer.customer_virtual = 'N'
        ";



if (isset($_REQUEST['id_account']) && !empty($_REQUEST['id_account'])) {
    $id_account = $_REQUEST['id_account'];
    $sql .= " AND tbl_account_account.id = '$id_account' ";
}

$time_complete = ($typeManager == 'request_payment') ? 'request_completed' : 'request_time_completed';

if (isset($_REQUEST['date_begin'])) {
    if ($_REQUEST['date_begin'] == '') {
        unset($_REQUEST['date_begin']);
    } else {
        $date_begin = strtotime($_REQUEST['date_begin'] . " 00:00:00");
        $sql .= " AND `$time_complete` >= '{$date_begin}'";
    }
} else {
    $month = date('Y-m',time());
    $three_month_ago = strtotime($month . "-01 00:00:00"); //7 776 000
    $sql .= " AND `$time_complete` >= '" . $three_month_ago . "'";
}

if (isset($_REQUEST['date_end'])) {
    if ($_REQUEST['date_end'] == '') {
        unset($_REQUEST['date_end']);
    } else {
        $date_end = strtotime($_REQUEST['date_end'] . " 23:59:59");
        $sql .= " AND `$time_complete` <= '{$date_end}'";
    }
} else {
    $month = time();
    $sql .= " AND `$time_complete` <= '" . $month . "'";
}

switch ($typeManager) {
    case 'request_payment':
        $sql .= " GROUP BY tbl_request_payment.id_customer
                 ";
        break;

    case 'request_deposit':
        $sql .= " GROUP BY tbl_request_deposit.id_customer
                 ";
        break;

    default:
        returnError("type_manager is not accept!");
        break;
}


$result_arr = array();
$result_arr['success'] = 'true';
$result_arr['data'] = array();
$result = db_qr($sql);
$nums = db_nums($result);
$total_temp = 0;
if ($nums > 0) {
    while ($row = db_assoc($result)) {
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
        if($typeManager == 'request_deposit'){
            $sql_check_total_bonus = "SELECT SUM(request_value) as total_bonus FROM tbl_request_bonus WHERE id_customer = '{$row['id_customer']}'";
            if (isset($id_account) && !empty($id_account)) {
                $sql_check_total_bonus .= " AND id_account = '$id_account'";
            }

            if (isset($date_begin) && !empty($date_begin) && isset($date_end) && !empty($date_end)) {
                $sql_check_total_bonus .= " AND ( request_time_completed  >= '$date_begin' AND request_time_completed <= '$date_end' )";
            }else{
                $sql_check_total_bonus .= " AND ( request_time_completed  >= '$three_month_ago' AND request_time_completed <= '$month' )";
            }
            $result_check_total_bonus = db_qr($sql_check_total_bonus);
                                    $nums_result_check_total_bonus = db_nums($result_check_total_bonus);
                                
                                    if($nums_result_check_total_bonus > 0){
                                        while($row_result_check_total_bonus = db_assoc($result_check_total_bonus)){
                                            $total_bonus += $row_result_check_total_bonus['total_bonus'];
                                        }
                                    }

            $result_item['total_money'] = strval($row['total_money'] - $total_bonus);
        }
        $total_temp += ($row['total_money'] - $total_bonus);
        array_push($result_arr['data'], $result_item);
    }
} 

$result_arr['total'] = strval($total_temp );
reJson($result_arr);
