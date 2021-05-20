<?php
if (isset($_REQUEST['time_break']) && !empty($_REQUEST['time_break'])) {
    $time_break = strval($_REQUEST['time_break']);
} else {
    $time_break = time();
}

$sql = "SELECT id,period_close FROM tbl_exchange_period 
        WHERE period_open <= '$time_break'
        AND period_close > '$time_break'";

$result = db_qr($sql);
$num = db_nums($result);

if ($num > 0) {
    while ($row = db_assoc($result)) {
        $id_session = $row['id'];
    }
} else {
    returnError('Chưa có phiên được tạo');
}

$sql_trade_up = "SELECT SUM(trading_bet) as total_money_up FROM tbl_trading_log 
                 WHERE id_exchange_period = '$id_session' 
                 AND trading_type = 'up'";

$result_trade_up = db_qr($sql_trade_up);
$nums_trade_up = db_nums($result_trade_up);

if ($nums_trade_up > 0) {
    while ($row_up = db_assoc($result_trade_up)) {
        $total_trade_up = $row_up['total_money_up'];
    }
}

$sql_trade_down = "SELECT SUM(trading_bet) as total_money_down FROM tbl_trading_log 
                 WHERE id_exchange_period = '$id_session' 
                 AND trading_type = 'down'";

$result_trade_down = db_qr($sql_trade_down);
$nums_trade_down = db_nums($result_trade_down);

if ($nums_trade_down > 0) {
    while ($row_down = db_assoc($result_trade_down)) {
        $total_trade_down = $row_down['total_money_down'];
    }
}

// Kiểm tra số lượng người tham gia
// Nếu bằng 1 thì kiểm tra xem đặt mấy cửa
// Nếu đặt 2 cửa thì trở về thuật toán đầu
// Nếu đặt 1 cửa, thì cửa còn lại bằng 0
// Kiểm tra nếu cửa này bằng 0, thì sẽ cho cửa ngược lại thắng
// Thắng sẽ được tính theo công thức (100 - tỷ lệ thắng)/2 = %thắng
// Đặt x = random từ 1-100, nếu x <= %thắng => người chơi đó win
// ==============> cần 1 tbl lưu tỷ lệ thắng theo id_customer
// Muốn lấy tỉ lệ thắng cần phải có id_customer -> duyệt từ tbl_trading_log ( nếu chỉ có 1 khách hàng )
// Tỷ lệ thắng của khách hàng phải được update sau khi kết thúc từng phiên thông qua socket
// ==============> Thuật toán update tỷ lệ thắng : ??
if($total_trade_down == '0' || $total_trade_up == '0'){
    $sql_total_customer = "SELECT COUNT(DISTINCT id_customer) as total_customer, id_customer 
                           FROM tbl_trading_log 
                           WHERE id_exchange_period = '$id_session' 
                           GROUP BY id_customer";
    $result_total_customer = db_qr($sql_total_customer);
    if(db_nums($result_total_customer)){
        while($row_total_customer = db_assoc($result_total_customer)){
            if((int)$row_total_customer['total_customer'] != 1 ){
                break;
            }else{
                $id_customer = $row_total_customer['id_customer'];
                // lấy tỉ lệ thắng

                $sql_total_trade_customer = "SELECT COUNT(id) as total_trade 
                                             FROM tbl_trading_log
                                             WHERE id_customer = '$id_customer'
                                            ";
                $sql_total_trade_customer_win = "SELECT COUNT(id) as total_trade_win
                                                 FROM tbl_trading_log
                                                 WHERE id_customer = '$id_customer'
                                                 AND trading_result = 'win'
                                                ";
                $result_total_trade_customer = db_qr($sql_total_trade_customer);
                $result_total_trade_customer_win = db_qr($sql_total_trade_customer_win);
                if(db_nums($result_total_trade_customer) > 0){
                    while($row_total_trade_customer = db_assoc($result_total_trade_customer)){
                        $total_trade = $row_total_trade_customer['total_trade'];
                    }
                }
                if(db_nums($result_total_trade_customer_win) > 0){
                    while($row_total_trade_customer_win = db_assoc($result_total_trade_customer_win)){
                        $total_trade_win = $row_total_trade_customer_win['total_trade_win'];
                    }
                }

                $percent_win = ((int)$total_trade/(int)$total_trade_win)*100;
                //////////////// STOP 


            }

        }
    }
}else{
    if ($total_trade_up < $total_trade_down) {
        $result_trade = "up";
        // insert tbl temporary
        insert_tbl_temporary($id_session, $result_trade);
    } elseif ($total_trade_up > $total_trade_down) {
        $result_trade = "down";
        // insert tbl temporary
        insert_tbl_temporary($id_session, $result_trade);
    } else {
        $result_trade_arr = array('up', 'down');
        $result_random = array_rand($result_trade_arr);
        $result_trade = $result_trade_arr[$result_random];
    
        if ($result_trade === 'up') {
            insert_tbl_temporary($id_session, $result_trade);
        } elseif ($result_trade === 'down') {
            insert_tbl_temporary($id_session, $result_trade);
        } else {
            returnError("result_trade null");
        }
    }
}
                                          

$result_arr = array();
$result_arr['success'] = "true";
$result_arr['data'] = array();
$result_item = array(
    'result_trade' => $result_trade,
);
array_push($result_arr['data'], $result_item);

reJson($result_arr);
