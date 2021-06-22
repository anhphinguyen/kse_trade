<?php

$sql = "SELECT 

        tbl_customer_customer.id as customer_id,
        tbl_customer_customer.customer_fullname,
        tbl_customer_customer.customer_wallet_bet,

            (SELECT SUM(tbl_request_bonus.request_value) FROM tbl_request_bonus WHERE id_customer = customer_id)  as total_bonus,
            (SELECT SUM(tbl_request_deposit.request_value) FROM tbl_request_deposit WHERE id_customer = customer_id) as total_deposit,
          
            (SELECT SUM(trading_bet) FROM tbl_trading_log WHERE trading_result = 'win' AND id_customer = customer_id)
            *(91/100)  as total_win, 
            (SELECT SUM(trading_bet) FROM tbl_trading_log WHERE trading_result = 'lose' AND id_customer = customer_id)  as total_lose,   
            (SELECT SUM(tbl_request_payment.request_value) FROM tbl_request_payment WHERE id_customer = customer_id AND request_status != '4')  as total_payment

        FROM tbl_customer_customer
        WHERE customer_virtual = 'N' AND customer_disable = 'N'
        AND tbl_customer_customer.id >= 1501 AND tbl_customer_customer.id <= 2712
        ";

// if (isset($_REQUEST['id_customer_from'])) {
//     if ($_REQUEST['id_customer_from'] == '') {
//         unset($_REQUEST['id_customer_from']);
        
//     } else {
//         $id_customer_from = $_REQUEST['id_customer_from'];
//         $sql .= " AND `tbl_customer_customer`.`id` >= '{$id_customer_from}'";
//     }
// } 
// if (isset($_REQUEST['id_customer_to'])) {
//     if ($_REQUEST['id_customer_to'] == '') {
//         unset($_REQUEST['id_customer_to']);
        
//     } else {
//         $id_customer_to = $_REQUEST['id_customer_to'];
//         $sql .= " AND `tbl_customer_customer`.`id` <= '{$id_customer_to}'";
//     }
// } 


if (isset($_REQUEST['id_customer'])) {
    if ($_REQUEST['id_customer'] == '') {
        unset($_REQUEST['id_customer']);
        
    } else {
        $id_customer = $_REQUEST['id_customer'];
        $sql .= " AND `tbl_customer_customer`.`id` = '{$id_customer}'";
    }
} 
if (isset($_REQUEST['filter'])) {
    if ($_REQUEST['filter'] == '') {
        unset($_REQUEST['filter']);
    } else {
        $filter = htmlspecialchars($_REQUEST['filter']);
        $sql .= " AND ( `customer_code` LIKE '%{$filter}%'";
        $sql .= " OR `customer_fullname` LIKE '%{$filter}%'";
        $sql .= " OR `customer_cert_no` LIKE '%{$filter}%'";
        $sql .= " OR `customer_phone` LIKE '%{$filter}%' )";
    }
}

// TT = total_deposit + total_bonus + total_win - total_lose - total_payment
// kiểm tra TT > customer_wallet_bet => Cộng thêm tiền
// kiểm tra TT < customer_wallet_bet => Thu hồi tiền
$result_arr = array();
$result = db_qr($sql);
$nums = db_nums($result);
if($nums > 0){
    while($row = db_assoc($result)){
        $total_current = 0;
        $total_bonus = 0;
        $total_deposit = 0;
        $total_win = 0;
        $total_lose = 0;
        $total_payment = 0;
        $customer_wallet_bet = $row['customer_wallet_bet'];
        if(!empty($row['total_bonus'])){
            $total_bonus = $row['total_bonus'];
        }
        if(!empty($row['total_deposit'])){
            $total_deposit = $row['total_deposit'];
        }
        if(!empty($row['total_win'])){
            $total_win = $row['total_win'];
        }
        if(!empty($row['total_lose'])){
            $total_lose = $row['total_lose'];
        }
        if(!empty($row['total_payment'])){
            $total_payment = $row['total_payment'];
        }

        $total_current = $total_deposit + $total_bonus + $total_win - $total_lose - $total_payment;

        $diff = 0;
        $type = "";
        if($total_current > $customer_wallet_bet){
            $type = "add_money";
            $diff = $total_current - $customer_wallet_bet;
        }elseif($total_current < $customer_wallet_bet){
            $type = "reset_money";
            $diff = $customer_wallet_bet - $total_current;
        }


        if(!empty($type)){
            $sql_update = "UPDATE tbl_customer_customer SET customer_wallet_bet = '$total_current' WHERE id = '{$row['customer_id']}'";
            db_qr($sql_update);
        }
    }
}
returnSuccess("true");