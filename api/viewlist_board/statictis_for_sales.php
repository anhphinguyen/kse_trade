<?php
// Báo cáo dành cho sales
// Kiếm được bao nhiêu lượt khách theo sales
// Tổng tiền khách nạp bao nhiêu theo sales
// Tổng tiền khách thắng bao nhiêu theo sales
// Tổng tiền khách thua bao nhiêu theo sales
// Tổng tiền âm ????
// if(isset($_REQUEST['id_account']) && !empty($_REQUEST['id_account'])){
//     $id_account = $_REQUEST['id_account'];
// }else{
//     returnError("Nhập id_account");
// }
if (isset($_REQUEST['account_code']) && !empty($_REQUEST['account_code'])) {
    $account_code = $_REQUEST['account_code'];
} else {
    returnError("Nhập account_code");
}

$sql_count_customer = "SELECT COUNT(id) as total_customer
                        FROM tbl_customer_customer 
                        WHERE tbl_customer_customer.customer_introduce = '$account_code'
                        ";
$sql_total_customer_bet = "SELECT 
                              SUM(customer_wallet_bet) as total_customer_bet
                        FROM tbl_customer_customer 
                        LEFT JOIN tbl_request_deposit
                        ON tbl_request_deposit.id_customer = tbl_customer_customer.id
                        WHERE tbl_customer_customer.customer_introduce = '$account_code'
                        AND tbl_customer_customer.customer_virtual = 'N'
                        ";

$sql_total_deposit = "SELECT
                        SUM(request_value) as total_deposit
                        FROM tbl_request_deposit
                        LEFT JOIN tbl_customer_customer 
                        ON tbl_request_deposit.id_customer = tbl_customer_customer.id
                        WHERE tbl_customer_customer.customer_introduce = '$account_code'
                        AND tbl_request_deposit.request_type = '1'
                        AND tbl_customer_customer.customer_virtual = 'N'
                        ";

// $sql_total_deposit = "SELECT
//                         SUM(request_value) as total_deposit
//                         FROM tbl_request_deposit
//                         LEFT JOIN tbl_customer_customer 
//                         ON tbl_request_deposit.id_customer = tbl_customer_customer.id
//                         WHERE tbl_customer_customer.customer_introduce = '$account_code'
//                         AND tbl_request_deposit.request_type = '1'
//                         AND tbl_customer_customer.customer_virtual = 'N'
//                         ";
$sql_total_win = "SELECT
                        SUM(trading_bet) as total_win
                        FROM tbl_trading_log
                        LEFT JOIN tbl_customer_customer 
                        ON tbl_trading_log.id_customer = tbl_customer_customer.id
                        WHERE tbl_customer_customer.customer_introduce = '$account_code'
                        AND tbl_trading_log.trading_result = 'win'
                        AND tbl_customer_customer.customer_virtual = 'N'
                        ";
$sql_total_lose = "SELECT
                        SUM(trading_bet) as total_lose
                        FROM tbl_trading_log
                        LEFT JOIN tbl_customer_customer 
                        ON tbl_trading_log.id_customer = tbl_customer_customer.id
                        WHERE tbl_customer_customer.customer_introduce = '$account_code'
                        AND tbl_trading_log.trading_result = 'lose'
                        AND tbl_customer_customer.customer_virtual = 'N'
                        ";

$sql_total_bonus = "SELECT
                    SUM(request_value) as total_bonus
                    FROM tbl_request_bonus
                    LEFT JOIN tbl_customer_customer 
                    ON tbl_request_bonus.id_customer = tbl_customer_customer.id
                    WHERE tbl_customer_customer.customer_introduce = '$account_code'
                    AND tbl_request_bonus.request_type = '1'
                    AND tbl_customer_customer.customer_virtual = 'N'
                    ";
$sql_total_bonus_type_2 = "SELECT
                    SUM(request_value) as total_bonus_type_2
                    FROM tbl_request_bonus
                    LEFT JOIN tbl_customer_customer 
                    ON tbl_request_bonus.id_customer = tbl_customer_customer.id
                    WHERE tbl_customer_customer.customer_introduce = '$account_code'
                    AND tbl_request_bonus.request_type = '2'
                    AND tbl_customer_customer.customer_virtual = 'N'
                    ";
$sql_total_deposit_type_2 = "SELECT
                        SUM(request_value) as total_deposit_type_2
                        FROM tbl_request_deposit
                        LEFT JOIN tbl_customer_customer 
                        ON tbl_request_deposit.id_customer = tbl_customer_customer.id
                        WHERE tbl_customer_customer.customer_introduce = '$account_code'
                        AND tbl_request_deposit.request_type = '2'
                        AND tbl_customer_customer.customer_virtual = 'N'
                        ";

$sql_total_payment = "SELECT
                        SUM(request_value) as total_payment
                        FROM tbl_request_payment
                        LEFT JOIN tbl_customer_customer 
                        ON tbl_request_payment.id_customer = tbl_customer_customer.id
                        WHERE tbl_customer_customer.customer_introduce = '$account_code'
                        AND tbl_customer_customer.customer_virtual = 'N'
                        ";

if (isset($_REQUEST['date_begin'])) {
    if ($_REQUEST['date_begin'] == '') {
        unset($_REQUEST['date_begin']);
    } else {
        $date_begin_customer = $_REQUEST['date_begin'] . " 00:00:00";
        $date_begin = strtotime($_REQUEST['date_begin'] . " 00:00:00");
        $sql_count_customer .= " AND tbl_customer_customer.customer_registered >= '{$date_begin_customer}'";
        $sql_total_deposit .= " AND tbl_request_deposit.request_time_completed >= '{$date_begin}'";
        $sql_total_win .= " AND tbl_trading_log.trading_log >= '{$date_begin}'";
        $sql_total_lose .= " AND tbl_trading_log.trading_log >= '{$date_begin}'";
        $sql_total_bonus .= " AND tbl_request_bonus.request_time_completed >= '{$date_begin}'";
        $sql_total_bonus_type_2 .= " AND tbl_request_bonus.request_time_completed >= '{$date_begin}'";

        $sql_total_deposit_type_2 .= " AND tbl_request_deposit.request_time_completed >= '{$date_begin}'";
        $sql_total_customer_bet .= " AND tbl_request_deposit.request_time_completed >= '{$date_begin}'";
        $sql_total_payment .= " AND tbl_request_payment.request_completed >= '{$date_begin}'";
    }
} else {
    $month = date('Y-m', time());
    $date_begin_customer = $month . "-01 00:00:00";

    $three_month_ago = strtotime($month . "-01 00:00:00"); //7 776 000

    $sql_count_customer .= " AND tbl_customer_customer.customer_registered >= '{$date_begin_customer}'";
    $sql_total_deposit .= " AND tbl_request_deposit.request_time_completed >= '{$three_month_ago}'";
    $sql_total_win .= " AND tbl_trading_log.trading_log >= '{$three_month_ago}'";
    $sql_total_lose .= " AND tbl_trading_log.trading_log >= '{$three_month_ago}'";
    $sql_total_bonus .= " AND tbl_request_bonus.request_time_completed >= '{$three_month_ago}'";
    $sql_total_bonus_type_2 .= " AND tbl_request_bonus.request_time_completed >= '{$three_month_ago}'";

    $sql_total_deposit_type_2 .= " AND tbl_request_deposit.request_time_completed >= '{$three_month_ago}'";
    $sql_total_customer_bet .= " AND tbl_request_deposit.request_time_completed >= '{$three_month_ago}'";
    $sql_total_payment .= " AND tbl_request_payment.request_completed >= '{$three_month_ago}'";
}

if (isset($_REQUEST['date_end'])) {
    if ($_REQUEST['date_end'] == '') {
        unset($_REQUEST['date_end']);
    } else {
        $date_end_customer = $_REQUEST['date_end'] . " 23:59:59";

        $date_end = strtotime($_REQUEST['date_end'] . " 23:59:59");
        $sql_count_customer .= " AND tbl_customer_customer.customer_registered <= '{$date_end_customer}'";
        $sql_total_deposit .= " AND tbl_request_deposit.request_time_completed <= '{$date_end}'";
        $sql_total_win .= " AND tbl_trading_log.trading_log <= '{$date_end}'";
        $sql_total_lose .= " AND tbl_trading_log.trading_log <= '{$date_end}'";
        $sql_total_bonus .= " AND tbl_request_bonus.request_time_completed <= '{$date_end}'";
        $sql_total_bonus_type_2 .= " AND tbl_request_bonus.request_time_completed <= '{$date_end}'";


        $sql_total_deposit_type_2 .= " AND tbl_request_deposit.request_time_completed <= '{$date_end}'";
        $sql_total_customer_bet .= " AND tbl_request_deposit.request_time_completed <= '{$date_end}'";
        $sql_total_payment .= " AND tbl_request_payment.request_completed <= '{$date_end}'";
    }
} else {
    $month = time();

    $date_end_customer = date("Y/m/d H:i:s", $month);

    $sql .= " AND `$time_complete` <= '" . $month . "'";
    $sql_count_customer .= " AND tbl_customer_customer.customer_registered <= '{$date_end_customer}'";
    $sql_total_deposit .= " AND tbl_request_deposit.request_time_completed <= '{$month}'";
    $sql_total_win .= " AND tbl_trading_log.trading_log <= '{$month}'";
    $sql_total_lose .= " AND tbl_trading_log.trading_log <= '{$month}'";
    $sql_total_bonus .= " AND tbl_request_bonus.request_time_completed <= '{$month}'";
    $sql_total_bonus_type_2 .= " AND tbl_request_bonus.request_time_completed <= '{$month}'";


    $sql_total_deposit_type_2 .= " AND tbl_request_deposit.request_time_completed <= '{$month}'";
    $sql_total_customer_bet .= " AND tbl_request_deposit.request_time_completed <= '{$month}'";
    $sql_total_payment .= " AND tbl_request_payment.request_completed <= '{$month}'";
}



$result_count_customer = db_qr($sql_count_customer);
$result_total_deposit = db_qr($sql_total_deposit);
$result_total_win = db_qr($sql_total_win);
$result_total_lose = db_qr($sql_total_lose);
$result_total_bonus = db_qr($sql_total_bonus);
$result_total_bonus_type_2 = db_qr($sql_total_bonus_type_2);
$result_total_deposit_type_2 = db_qr($sql_total_deposit_type_2);
$result_total_payment = db_qr($sql_total_payment);
$result_total_customer_bet = db_qr($sql_total_customer_bet);

$nums_count_customer = db_nums($result_count_customer);
$nums_total_deposit = db_nums($result_total_deposit);
$nums_total_win = db_nums($result_total_win);
$nums_total_lose = db_nums($result_total_lose);
$nums_total_bonus = db_nums($result_total_bonus);
$nums_total_bonus_type_2 = db_nums($result_total_bonus_type_2);
$nums_total_deposit_type_2 = db_nums($result_total_deposit_type_2);
$nums_total_customer_bet = db_nums($result_total_customer_bet);
$nums_total_payment = db_nums($result_total_payment);

$total_customer = '0';
$total_deposit = '0';
$total_win = '0';
$total_bonus = '0';
$total_bonus_type_2 = '0';
$total_deposit_type_2 = '0';
$total_lose =  '0';
$total_customer_bet = '0';
$total_payment =  '0';


$total_am = '0';

if ($nums_count_customer > 0) {
    while ($row_count_customer = db_assoc($result_count_customer)) {
        $total_customer = $row_count_customer['total_customer'];
    }
}
if ($nums_total_deposit > 0) {
    while ($row_total_deposit = db_assoc($result_total_deposit)) {
        $total_deposit = $row_total_deposit['total_deposit'];
    }
}
if ($nums_total_win > 0) {
    while ($row_total_win = db_assoc($result_total_win)) {
        $total_win = $row_total_win['total_win'];
    }
}
if ($nums_total_lose > 0) {
    while ($row_total_lose = db_assoc($result_total_lose)) {
        $total_lose = $row_total_lose['total_lose'];
    }
}
if ($nums_total_bonus > 0) {
    while ($row_total_bonus = db_assoc($result_total_bonus)) {
        $total_bonus = $row_total_bonus['total_bonus'];
    }
}
if ($nums_total_bonus_type_2 > 0) {
    while ($row_total_bonus_type_2 = db_assoc($result_total_bonus_type_2)) {
        $total_bonus_type_2 = $row_total_bonus_type_2['total_bonus_type_2'];
    }
}
if ($nums_total_deposit_type_2 > 0) {
    while ($row_total_deposit_type_2 = db_assoc($result_total_deposit_type_2)) {
        $total_deposit_type_2 = $row_total_deposit_type_2['total_deposit_type_2'];
    }
}
if ($nums_total_payment > 0) {
    while ($row_total_payment = db_assoc($result_total_payment)) {
        $total_payment = $row_total_payment['total_payment'];
    }
}
if ($nums_total_customer_bet > 0) {
    while ($row_total_customer_bet = db_assoc($result_total_customer_bet)) {
        $total_customer_bet = strval((int)$row_total_customer_bet['total_customer_bet']);
    }
}


// $total_am = strval((int)$total_lose - (int)$total_bonus - (int)$total_bonus_type_2 - (int)$total_deposit_type_2 - (int)$total_customer_bet);
$total_am = strval((int)$total_deposit - (int)$total_payment - (int)$total_customer_bet);

$result_arr = array();
$result_arr['success'] = "true";
$result_arr['total_customer'] = (!empty($total_customer)) ? $total_customer : "0";
$result_arr['total_deposit'] = (!empty($total_deposit)) ? $total_deposit : "0";
$result_arr['total_win'] = (!empty($total_win)) ? $total_win : "0";
$result_arr['total_lose'] = (!empty($total_lose)) ? $total_lose : "0";
$result_arr['total_customer_bet'] = (!empty($total_customer_bet)) ? $total_customer_bet : "0";
$result_arr['total_bonus'] = (!empty($total_bonus)) ? $total_bonus : "0";
$result_arr['total_bonus_type_2'] = (!empty($total_bonus_type_2)) ? $total_bonus_type_2 : "0";
$result_arr['total_deposit_type_2'] = (!empty($total_deposit_type_2)) ? $total_deposit_type_2 : "0";
$result_arr['total_payment'] = (!empty($total_payment)) ? $total_payment : "0";
$result_arr['total_am'] = (!empty($total_am)) ? $total_am : "0";
$result_arr['data'] = array();

reJson($result_arr);
