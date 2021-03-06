<?php

$typeManager = '';

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

    case 'update': {
            if (isset($_REQUEST['id_exchange']) && !empty($_REQUEST['id_exchange'])) {
                $id_exchange = $_REQUEST['id_exchange'];
            } else {
                returnError("Nhập id_exchange");
            }

            if (isset($_REQUEST['id_account']) && !empty($_REQUEST['id_account'])) {
                $id_account = $_REQUEST['id_account'];
            } else {
                returnError("Nhập id_account");
            }

            if (isset($_REQUEST['exchange_open']) && !empty($_REQUEST['exchange_open'])) {
                if (date("d", strtotime($_REQUEST['exchange_open'])) <= date("d", time())) {
                    returnError("Chỉ được cập nhật thời gian mở sàn cho ngày hôm sau");
                }
                $time_open = strtotime($_REQUEST['exchange_open']);
            }else {
                returnError("Nhập exchange_open");
            }


            if (isset($_REQUEST['exchange_close']) && !empty($_REQUEST['exchange_close'])) {
                if (date("d", strtotime($_REQUEST['exchange_close'])) <= date("d", time())) {
                    returnError("Chỉ được cập nhật thời gian đóng sàn cho ngày hôm sau");
                } elseif (strtotime($_REQUEST['exchange_close']) == strtotime($_REQUEST['exchange_open'])) {
                    returnError("Thời gian đóng sàn không được trùng với thời gian mở sàn");
                }
                $time_close = strtotime($_REQUEST['exchange_close']);
            }else {
                returnError("Nhập exchange_close");
            }

            if (isset($_REQUEST['exchange_period']) && !empty($_REQUEST['exchange_period'])) {
                $time_living = $_REQUEST['exchange_period'];
            }else {
                returnError("Nhập exchange_period");
            }

            $sql = "SELECT * FROM tbl_exchange_temporary WHERE id_exchange = '$id_exchange'";
            $result = db_qr($sql);
            $nums = db_nums($result);
            if ($nums > 0) {
                while ($row = db_assoc($result)) {
                    $sql = "UPDATE tbl_exchange_temporary SET
                                exchange_open = '$time_open',
                                exchange_close = '$time_close',
                                exchange_period = '$time_living',
                                exchange_updated_by = '$id_account'
                                WHERE id_exchange = '$id_exchange'
                                ";
                    if (db_qr($sql)) {
                        returnSuccess("Sàn mới sẽ bắt đầu từ lúc " . date("d/m/Y H:i:s", $time_open));
                    } else {
                        returnError("Lỗi truy vấn");
                    }
                }
            } else {
                $sql = "INSERT INTO tbl_exchange_temporary SET
                id_exchange = '$id_exchange',
                exchange_open = '$time_open',
                exchange_close = '$time_close',
                exchange_period = '$time_living',
                exchange_updated_by = '$id_account'
                ";
                if (db_qr($sql)) {
                    returnSuccess("Sàn mới sẽ bắt đầu từ lúc " . date("d/m/Y H:i:s", $time_open));
                } else {
                    returnError("Lỗi truy vấn");
                }
            }


            break;
        }

    case 'list_exchange': {
            $result_arr = array();
            $sql = "SELECT * FROM tbl_exchange_exchange";
            $result = db_qr($sql);
            $nums = db_nums($result);
            if ($nums > 0) {
                $result_arr['success'] = 'true';
                $result_arr['data'] = array();
                while ($row = db_assoc($result)) {

                    $sql_temporary = "SELECT * FROM tbl_exchange_temporary WHERE id_exchange = '".$row['id']."'";
                    $result_temporary = db_qr($sql_temporary);
                    $nums_temporary = db_nums($result_temporary);
                    if($nums_temporary > 0){
                        while($row_temporary = db_assoc($result_temporary)){
                            $temporary_exchange_open = $row_temporary['exchange_open'];
                            $temporary_exchange_close = $row_temporary['exchange_close'];
                            $temporary_exchange_period = $row_temporary['exchange_period'];
                            $temporary_exchange_updated_by = $row_temporary['exchange_updated_by'];
                            $temporary_exchange_quantity = strval(floor(((int)$row_temporary['exchange_close'] - (int)$row_temporary['exchange_open'])/(int)$row_temporary['exchange_period']));
                        }
                    }
                    $exchange_quantity = get_exchange_quantity($row['id']);
                    $result_item = array(
                        'id_exchange' => $row['id'],
                        'exchange_name' => $row['exchange_name'],
                        'exchange_active' => $row['exchange_active'],
                        'exchange_open' => date("H:i", $row['exchange_open']),
                        'exchange_close' => date("H:i", $row['exchange_close']),
                        'exchange_quantity' => strval($exchange_quantity),
                        'exchange_period' => strval($row['exchange_period'] / 60),
                        'exchange_updated_by' => (isset($row['exchange_updated_by']) && !empty($row['exchange_updated_by'])) ? $row['exchange_updated_by'] : "0",

                        'temporary_exchange_open' =>  (isset($temporary_exchange_open)&&!empty($temporary_exchange_open))?date("H:i",$temporary_exchange_open):"",
                        'temporary_exchange_close' => (isset($temporary_exchange_close)&&!empty($temporary_exchange_close))?date("H:i",$temporary_exchange_close):"",
                        'temporary_exchange_quantity' => (isset($temporary_exchange_quantity)&&!empty($temporary_exchange_quantity))?$temporary_exchange_quantity:"",
                        'temporary_exchange_period' => (isset($temporary_exchange_period)&&!empty($temporary_exchange_period))?$temporary_exchange_period:"",
                        'temporary_exchange_updated_by' => (isset($temporary_exchange_updated_by)&&!empty($temporary_exchange_updated_by))?$temporary_exchange_updated_by:"",
                    );
                    array_push($result_arr['data'], $result_item);
                }
                reJson($result_arr);
            }
            break;
        }

    case 'detail_exchange_trade':
        if (isset($_REQUEST['id_exchange'])) {
            if ($_REQUEST['id_exchange'] == '') {
                unset($_REQUEST['id_exchange']);
                returnError("Nhập id_exchange");
            } else {
                $id_exchange = $_REQUEST['id_exchange'];
            }
        } else {
            returnError("Nhập id_exchange");
        }

        $time_present = time();

        $sql = "SELECT * FROM tbl_exchange_period 
                WHERE period_open <= '$time_present'
                AND period_close > '$time_present'";
        $result = db_qr($sql);
        $num = db_nums($result);
        if ($num > 0) {
            while ($row = db_assoc($result)) {
                $id_session = $row['id'];
            }
        } 



        $sql_people_up = "SELECT COUNT(id) as total_people_up FROM tbl_trading_log
                WHERE trading_type = 'up'
                AND id_exchange_period = '$id_session'
                ";
        $result_people_up = db_qr($sql_people_up);
        $nums_people_up = db_nums($result_people_up);
        if ($nums_people_up > 0) {
            while ($row_people_up = db_assoc($result_people_up)) {
                $total_people_up = $row_people_up['total_people_up'];
            }
        }

        $sql_money_up = "SELECT SUM(trading_bet) as total_money_up FROM tbl_trading_log
                WHERE trading_type = 'up'
                AND id_exchange_period = '$id_session'
                ";
        $result_money_up = db_qr($sql_money_up);
        $nums_money_up = db_nums($result_money_up);
        if ($nums_money_up > 0) {
            while ($row_money_up = db_assoc($result_money_up)) {
                $total_money_up = $row_money_up['total_money_up'];
            }
        }

        $sql_people_down = "SELECT COUNT(id) as total_people_down FROM tbl_trading_log
                WHERE trading_type = 'down'
                AND id_exchange_period = '$id_session'
                ";
        $result_people_down = db_qr($sql_people_down);
        $nums_people_down = db_nums($result_people_down);
        if ($nums_people_down > 0) {
            while ($row_people_down = db_assoc($result_people_down)) {
                $total_people_down = $row_people_down['total_people_down'];
            }
        }
        $sql_money_down = "SELECT SUM(trading_bet) as total_money_down FROM tbl_trading_log
                WHERE trading_type = 'down'
                AND id_exchange_period = '$id_session'
                ";
        $result_money_down = db_qr($sql_money_down);
        $nums_money_down = db_nums($result_money_down);
        if ($nums_money_down > 0) {
            while ($row_money_down = db_assoc($result_money_down)) {
                $total_money_down = $row_money_down['total_money_down'];
            }
        }

        $result_arr = array();
        $sql = "SELECT * FROM tbl_exchange_exchange WHERE id = '$id_exchange'";
        $result = db_qr($sql);
        $nums = db_nums($result);
        if ($nums > 0) {
            $result_arr['success'] = 'true';
            $result_arr['data'] = array();
            while ($row = db_assoc($result)) {

                $exchange_quantity = get_exchange_quantity($row['id']);
                $result_item = array(
                    'id_exchange' => $row['id'],
                    'exchange_name' => $row['exchange_name'],
                    'exchange_open' => date("H:i", $row['exchange_open']),
                    'exchange_close' => date("H:i", $row['exchange_close']),
                    'exchange_quantity' => strval($exchange_quantity),
                    'exchange_period' => strval($row['exchange_period'] / 60),
                    'exchange_updated_by' => (isset($row['exchange_updated_by']) && !empty($row['exchange_updated_by'])) ? $row['exchange_updated_by'] : "0",
                    'total_people_up' => (isset($total_people_up)&& !empty($total_people_up))?$total_people_up:"0",
                    'total_people_down' => (isset($total_people_down)&& !empty($total_people_down))?$total_people_down:"0",
                    'total_money_up' => (isset($total_money_up)&& !empty($total_money_up))?$total_money_up:"0",
                    'total_money_down' =>(isset($total_money_down)&& !empty($total_money_down))?$total_money_down:"0", 
                );
                array_push($result_arr['data'], $result_item);
            }
            reJson($result_arr);
        }
        break;

    default:
        returnError("type_manager is not accept!");
        break;
}
