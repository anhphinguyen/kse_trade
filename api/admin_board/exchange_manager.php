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
                return ("Nhập id_exchange");
            }

            if (isset($_REQUEST['id_account']) && !empty($_REQUEST['id_account'])) {
                $id_account = $_REQUEST['id_account'];
            } else {
                return ("Nhập id_account");
            }

            if (isset($_REQUEST['time_open']) && !empty($_REQUEST['time_open'])) {
                if (date("d", $_REQUEST['time_open']) <= date("d", time())) {
                    returnError("Chỉ được cập nhật thời gian mở sàn cho ngày hôm sau");
                }
                $time_open = $_REQUEST['time_open'];
            }


            if (isset($_REQUEST['time_close']) && !empty($_REQUEST['time_close'])) {
                if (date("d", $_REQUEST['time_close']) <= date("d", time())) {
                    returnError("Chỉ được cập nhật thời gian đóng sàn cho ngày hôm sau");
                } elseif ($_REQUEST['time_close'] == $_REQUEST['time_open']) {
                    returnError("Thời gian đóng sàn không được trùng với thời gian mở sàn");
                }
                $time_close = $_REQUEST['time_close'];
            }

            if (isset($_REQUEST['time_living']) && !empty($_REQUEST['time_living'])) {
                $time_living = $_REQUEST['time_living'];
            }

            $sql = "INSERT INTO tbl_exchange_temporary SET
                id_exchange = '$id_exchange',
                exchange_open = '$time_open',
                exchange_close = '$time_close',
                exchange_period = '$time_living',
                exchange_update_by = '$id_account'
                ";
            if (db_qr($sql)) {
                returnSuccess("Sàn mới sẽ bắt đầu từ lúc " . date("d/m/Y H:i:s", $time_open));
            } else {
                returnError("Lỗi truy vấn");
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

                    $exchange_quantity = get_exchange_quantity($row['id']);
                    $result_item = array(
                        'id_exchange' => $row['id'],
                        'exchange_name' => $row['exchange_name'],
                        'exchange_open' => date("H:i", $row['exchange_open']),
                        'exchange_close' => date("H:i", $row['exchange_close']),
                        'exchange_quantity' => strval($exchange_quantity),
                        'exchange_period' => strval($row['exchange_period'] / 60),
                        'exchange_updated_by' => (isset($row['exchange_updated_by']) && !empty($row['exchange_updated_by'])) ? $row['exchange_updated_by'] : "0",
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
        } else {
            returnError('Chưa có phiên được tạo');
        }


        $total_people_up = "";
        $total_people_down = "";
        $total_money_up = "";
        $total_money_down = "";

        $sql_people_up = "SELECT SUM(id_customer) as total_people_up FROM tbl_trading_log
                WHERE trading_type = 'UP'
                AND id_session = '$id_session'
                GROUP BY id_customer
                ";
        $result_people_up = db_qr($sql_people_up);
        $nums_people_up = db_nums($result_people_up);
        if ($nums_people_up > 0) {
            while ($row_people_up = db_assoc($result_people_up)) {
                $total_people_up = $row_people_up['total_people_up'];
            }
        }

        $sql_money_up = "SELECT SUM(trading_bet) as total_money_up FROM tbl_trading_log
                WHERE trading_type = 'UP'
                AND id_session = '$id_session'
                ";
        $result_money_up = db_qr($sql_money_up);
        $nums_money_up = db_nums($result_money_up);
        if ($nums_money_up > 0) {
            while ($row_money_up = db_assoc($result_money_up)) {
                $total_money_up = $row_money_up['total_people_up'];
            }
        }

        $sql_people_down = "SELECT SUM(id_customer) as total_people_down FROM tbl_trading_log
                WHERE trading_type = 'DOWN'
                AND id_session = '$id_session'
                GROUP BY id_customer
                ";
        $result_people_down = db_qr($sql_people_down);
        $nums_people_down = db_nums($result_people_down);
        if ($nums_people_down > 0) {
            while ($row_people_down = db_assoc($result_people_down)) {
                $total_people_down = $row_people_down['total_people_down'];
            }
        }
        $sql_money_down = "SELECT SUM(trading_bet) as total_money_down FROM tbl_trading_log
                WHERE trading_type = 'DOWN'
                AND id_session = '$id_session'
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
                    'total_people_up' => $total_people_up,
                    'total_people_down' => $total_people_down,
                    'total_money_up' => $total_money_up,
                    'total_money_down' => $total_money_down,
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
