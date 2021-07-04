<?php
$result_arr = array();
$sql = "SELECT id, customer_percent_win, customer_total_trade FROM tbl_customer_customer WHERE 1=1";

if(isset($_REQUEST['id_begin']) && !empty($_REQUEST['id_begin'])){
    $id_begin = (int)$_REQUEST['id_begin'];
    $sql .= " AND id >= '$id_begin'";
}
if(isset($_REQUEST['id_end']) && !empty($_REQUEST['id_end'])){
    $id_end = (int)$_REQUEST['id_end'];
    $sql .= " AND id < '$id_end'";
}

$result = db_qr($sql);
if (db_nums($result) > 0) {
    $success = "false";
    $result_arr['success'] = "false";
    $result_arr['data'] = array();

    while ($row = db_assoc($result)) {
        $id_customer = $row['id'];
        $customer_percent_win = $row['customer_percent_win'];
        $customer_total_trade = $row['customer_total_trade'];

        $sql_get_time_trade_nearly = "SELECT MAX(trading_log) as customer_timebet_nearly 
                                        FROM tbl_trading_log 
                                        WHERE id_customer = '$id_customer'";
        $sql_total_trade_customer = "SELECT COUNT(id) as total_trade 
                                                 FROM tbl_trading_log
                                                 WHERE id_customer = '$id_customer'
                                                ";
        $sql_total_trade_customer_win = "SELECT COUNT(id) as total_trade_win
                                                     FROM tbl_trading_log
                                                     WHERE id_customer = '$id_customer'
                                                     AND trading_result = 'win'
                                                    ";
        $result_get_time_trade_nearly = db_qr($sql_get_time_trade_nearly);
        $result_total_trade_customer = db_qr($sql_total_trade_customer);
        $result_total_trade_customer_win = db_qr($sql_total_trade_customer_win);
        if (db_nums($result_get_time_trade_nearly) > 0) {
            while ($row_get_time_trade_nearly = db_assoc($result_get_time_trade_nearly)) {
                $customer_timebet_nearly = $row_get_time_trade_nearly['customer_timebet_nearly'];
            }
        }
        if (db_nums($result_total_trade_customer) > 0) {
            while ($row_total_trade_customer = db_assoc($result_total_trade_customer)) {
                $total_trade = $row_total_trade_customer['total_trade'];
            }
        }
        if (db_nums($result_total_trade_customer_win) > 0) {
            while ($row_total_trade_customer_win = db_assoc($result_total_trade_customer_win)) {
                $total_trade_win = $row_total_trade_customer_win['total_trade_win'];
            }
        }
        // auto
        if( isset($total_trade) && !empty($total_trade) && (int)$total_trade != 0){
            $total_trade_update = (int)$customer_total_trade + (int)$total_trade; 
            $percent_win = ((int)$total_trade_win / (int)$total_trade) * 100;
        }else{
            $total_trade_update = (int)$customer_total_trade;
            $percent_win = 0;
        }
        
        if($customer_percent_win != 0){
            if($percent_win != 0){
                $percent_win_update = floor(($customer_percent_win + $percent_win)/2);
            }else{
                $percent_win_update = $customer_percent_win;
            }
        }else{
            $percent_win_update = $percent_win;
        }

        $sql_update = "UPDATE tbl_customer_customer SET 
                        customer_percent_win = '$percent_win_update',
                        customer_total_trade = '$total_trade_update'";
                
        if(isset($customer_timebet_nearly) && !empty($customer_timebet_nearly)){
            $sql_update .=",customer_timebet_nearly = '$customer_timebet_nearly'";
        }
        $sql_update .=" WHERE id = '$id_customer'";

        if(db_qr($sql_update)){
            $sql_delete_trading_log = "DELETE FROM tbl_trading_log WHERE id_customer = '$id_customer'";
            if(db_qr($sql_delete_trading_log)){
                $result_arr['success'] = "true";
            };

        }else{
            returnError("Cập nhật không thành công");
        };
    }

    $sql_delete_trading_log_demo = "DELETE FROM tbl_customer_demo_log WHERE 1";
    db_qr($sql_delete_trading_log_demo);
    $sql_delete_customer_demo = "DELETE FROM tbl_customer_demo WHERE 1";
    db_qr($sql_delete_customer_demo);
}
reJson($result_arr);

