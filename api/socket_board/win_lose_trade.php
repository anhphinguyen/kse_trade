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
        $total_trade_up = (!empty($row_up['total_money_up'])) ? $row_up['total_money_up'] : '0';
    }
}

$sql_trade_down = "SELECT SUM(trading_bet) as total_money_down FROM tbl_trading_log 
                 WHERE id_exchange_period = '$id_session' 
                 AND trading_type = 'down'";

$result_trade_down = db_qr($sql_trade_down);
$nums_trade_down = db_nums($result_trade_down);

if ($nums_trade_down > 0) {
    while ($row_down = db_assoc($result_trade_down)) {
        $total_trade_down = (!empty($row_down['total_money_down'])) ? $row_down['total_money_down'] : '0';
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

if (($total_trade_down == '0' && $total_trade_up > '0')  || ($total_trade_up == '0' && $total_trade_down > '0')) {


    $percent_system = "0";
    $percent_status = "N";
    $percent_winlose_one_customer = 0;
    $customer_factor = 0;
    $sql_get_percent_system = "SELECT percent_status, percent_system FROM tbl_percent_system
                                WHERE id = '1'";
    $result_get_percent_system = db_qr($sql_get_percent_system);
    if (db_nums($result_get_percent_system) > 0) {
        while ($row_get_percent_system = db_assoc($result_get_percent_system)) {
            $percent_system = (int)$row_get_percent_system['percent_system'];
            $percent_status = $row_get_percent_system['percent_status'];
        }
    }


    $sql_total_customer = "SELECT COUNT(DISTINCT id_customer) as total_customer, id_customer, trading_type 
                           FROM tbl_trading_log 
                           WHERE id_exchange_period = '$id_session' 
                           ";
    $result_total_customer = db_qr($sql_total_customer);
    if (db_nums($result_total_customer)) {
        while ($row_total_customer = db_assoc($result_total_customer)) {


            // // lấy hệ số khách hàng
            // $customer_factor = (int)$row_total_customer['customer_factor'];
            if ((int)$row_total_customer['total_customer'] === 1) {

                // lây id_customer
                $id_customer = $row_total_customer['id_customer'];

                $sql_get_customer_factor = "SELECT customer_factor FROM tbl_customer_customer WHERE id = '$id_customer'";
                $result_get_customer_factor = db_qr($sql_get_customer_factor);
                if (db_nums($result_get_customer_factor) > 0) {
                    while ($row_get_customer_factor = db_assoc($result_get_customer_factor)) {
                        $customer_factor = (int)$row_get_customer_factor['customer_factor'];
                    }
                }


                // lây trading type
                $trading_type = $row_total_customer['trading_type'];
                if ($percent_status === "Y") {
                    if ($percent_system != 0) {
                        // system
                        // percent_winlose_one_customer
                        $percent_winlose_one_customer = $percent_system * (int)$customer_factor;
                    } else {
                        // lấy tỉ lệ thắng của khách hàng

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
                        $percent_win = ((int)$total_trade_win / (int)$total_trade) * 100;
                        // percent_winlose_one_customer
                        $percent_winlose_one_customer = ((100 - $percent_win) / 2) * (int)$customer_factor;
                    }
                    // Tỷ lệ random
                    $radom = rand(1, 100);
                    if ($radom <= $percent_winlose_one_customer) {
                        insert_tbl_temporary($id_session, $trading_type);
                    } else {
                        if ($trading_type === 'up') {
                            insert_tbl_temporary($id_session, 'down');
                        } else {
                            insert_tbl_temporary($id_session, 'up');
                        }
                    }
                }
            } else {
                if ($total_trade_down == '0') {
                    insert_tbl_temporary($id_session, 'down');
                } else {
                    insert_tbl_temporary($id_session, 'up');
                }
            }
        }
    }
} else {
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
