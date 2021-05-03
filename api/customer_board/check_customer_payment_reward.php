<?php

if (isset($_REQUEST['id_customer']) && !empty($_REQUEST['id_customer'])) {
    $id_customer = $_REQUEST['id_customer'];
} else {
    returnError("Nhập id_customer");
}
$sql = "SELECT * FROM tbl_customer_customer WHERE id = '$id_customer'";
$result = db_qr($sql);
$nums = db_nums($result);
$customer_wallet_reward_update = 0;
if ($nums > 0) {
    while ($row = db_assoc($result)) {
        $customer_wallet_reward_update = (int)$row['customer_wallet_rewards'];
    }
}
if($customer_wallet_reward_update == 0){
    returnError("Bạn chưa đạt đủ điều kiện để rút tiền khuyến mãi");
}

//check dieu kien khuyen mai value bet > 10 lan value khuyen mai
$sql_check_time_request_add_KM = "SELECT * 
FROM tbl_request_deposit
WHERE id_customer = '$id_customer'
ORDER BY `tbl_request_deposit`.`request_time_completed` DESC LIMIT 1";

$result_check_time_request_add_KM = db_qr($sql_check_time_request_add_KM);    
$data_time_request_add_KM = 0;
if (db_nums($result_check_time_request_add_KM) > 0) {
while ($row_check_time_request_add_KM = db_assoc($result_check_time_request_add_KM)) {
    $data_time_request_add_KM = $row_check_time_request_add_KM['request_time_completed'];
}
}
$total_bet_after_reward_time = 0;

$sql_check_total_bet_after_reward_time = "SELECT SUM(trading_bet) as bet_total  
FROM tbl_trading_log 
WHERE id_customer = '$id_customer' AND trading_log >= '$data_time_request_add_KM'";

$result_check_total_bet_after_reward_time = db_qr($sql_check_total_bet_after_reward_time);    
if (db_nums($result_check_total_bet_after_reward_time) > 0) {
while ($row_check_total_bet_after_reward_time = db_assoc($result_check_total_bet_after_reward_time)) {
    $total_bet_after_reward_time = !empty($row_check_total_bet_after_reward_time['bet_total']) ? $row_check_total_bet_after_reward_time['bet_total']: 0;
}
}
if($total_bet_after_reward_time >= $customer_wallet_reward_update * 10){
returnSuccess("Bạn có thể rút tiền từ ví khuyến mãi!");
}else{
returnError("Bạn chưa đạt đủ điều kiện để rút tiền khuyến mãi");
}
