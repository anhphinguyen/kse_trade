<?php

if (isset($_REQUEST['id_customer']) && !empty($_REQUEST['id_customer'])) {
    $id_customer = $_REQUEST['id_customer'];
} else {
    returnError("Nhập id_customer");
}

if (isset($_REQUEST['request_value']) && !empty($_REQUEST['request_value'])) {
    $request_value = $_REQUEST['request_value'];
} else {
    returnError("Nhập request_value");
}

$request_type = '1';
            if (isset($_REQUEST['request_type']) && !(empty($_REQUEST['request_type']))) {
                $request_type = $_REQUEST['request_type'];
            }

$sql = "SELECT * FROM tbl_customer_customer WHERE id = '$id_customer'";
$result = db_qr($sql);
$nums = db_nums($result);
if ($nums > 0) {
    while ($row = db_assoc($result)) {

        if ($row['id_bank'] == 0) {
            returnError("Bạn chưa tạo phương thức thanh toán !");
        } elseif ($row['customer_wallet_bet'] == '0') {
            returnError("Bạn không có tiền trong ví");
        } elseif ($request_value > (int)$row['customer_wallet_bet']) {
            returnError("Số tiền bạn rút vượt quá tài khoản trong ví");
        } elseif ($request_value < '700000') {
            returnError("Bạn cần rút tối thiểu là 700.000 VND");
        }

        $customer_paymented = get_customer_paymented_in_day($id_customer);
        // so sánh tiền hạn mức
        if ($customer_paymented > $row['customer_limit_payment']) {
            returnError("Bạn đã vượt quá hạn mức giao dịch trong ngày");
        }
        $customer_wallet_pet_update = (int)$row['customer_wallet_bet'];
        $customer_wallet_reward_update = (int)$row['customer_wallet_rewards'];
        
        if($request_type == '1'){
            $customer_wallet_pet_update = (int)$row['customer_wallet_bet'] - $request_value;
        }else{
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
                $customer_wallet_reward_update = (int)$row['customer_wallet_rewards'] - $request_value;
            }else{
                returnError("Bạn chưa đạt đủ điều kiện để rút tiền khuyến mãi");
            }
            

        }
        
        $customer_wallet_payment_update = (int)$row['customer_wallet_payment'] + $request_value;

        $sql = "UPDATE tbl_customer_customer SET 
                customer_wallet_bet = '$customer_wallet_pet_update',
                customer_wallet_rewards = '$customer_wallet_reward_update',
                customer_wallet_payment = '$customer_wallet_payment_update'
                WHERE id = '$id_customer'
                ";
        if (db_qr($sql)) {
            $request_code = "RT" . substr(time(), -8);
            $request_created = time();
            $request_created_before = $request_created - 7 * 24 * 60 * 60;
            $count = 1;

            $num_current_weekOfMonth = checkweekOfMonth($request_created);

            $sql_check_weekOfMonth = "SELECT * FROM tbl_request_payment 
                                    WHERE id_customer = '$id_customer'  
                                    AND request_created >= '$request_created_before' 
                                    AND request_created <= '$request_created'
                                    AND request_status < '4'
                                    ";
            $result_check_weekOfMonth = db_qr($sql_check_weekOfMonth);

            $request_created_arr = array();
            if (db_nums($result_check_weekOfMonth) > 0) {
                while ($row_check_weekOfMonth = db_assoc($result_check_weekOfMonth)) {
                    $data_request_created = array(
                        'request_created' => $row_check_weekOfMonth['request_created']
                    );
                    array_push($request_created_arr, $data_request_created);
                }
            }

            if (count(($request_created_arr)) > 0) {
                for ($i = count($request_created_arr) - 1; $i >= 0; $i--) {
                    $num_weekOfMonth = checkweekOfMonth($request_created_arr[$i]['request_created']);
                    if ($num_weekOfMonth == $num_current_weekOfMonth) {
                        $count++;
                    } else {
                        break;
                    }
                }
            }
            
            $request_fee = get_discount($count);

            $sql = "INSERT INTO tbl_request_payment SET
                id_customer = '$id_customer',
                request_code = '$request_code',
                request_value = '$request_value',
                request_created = '$request_created',
                request_fee = '$request_fee',
                request_type = '$request_type',
                request_status = '1'
                ";
            if (db_qr($sql)) {
                $title = "Thông báo có yêu cầu rút tiền!!!";
                $bodyMessage = "Có yêu cầu rút tiền từ khách hàng!";
                $action = "officer_payment";
                $type_send = 'topic';
                $to = 'KSE_officer_payment';
                pushNotification($title, $bodyMessage, $action, $to, $type_send);
                returnSuccess("Gửi yêu cầu rút tiền thành công");
            } else {
                returnError("Lỗi truy vấn");
            }
        }
    }
} else {
    returnError("Không tồn tại khách hàng này");
}
