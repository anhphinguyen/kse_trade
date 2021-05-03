<?php
if (isset($_REQUEST['type_target'])) {
    if ($_REQUEST['type_target'] == '') {
        unset($_REQUEST['type_target']);
        returnError("Nhập type_target");
    } else {
        $type_manager = $_REQUEST['type_target'];
    }
} else {
    returnError("Nhập type_target");
}

switch ($type_manager) {
    case 'customer_datail':{
        break;
    }
    case 'list_customer': {
            $sql = "SELECT account_code FROM tbl_account_account WHERE 1=1 ";

            if (isset($_REQUEST['id_account']) && !empty($_REQUEST['id_account'])) {
                $id_account = $_REQUEST['id_account'];
                $sql .= " AND id = '$id_account'";
            }

            if (isset($_REQUEST['type_sort'])) {
                if ($_REQUEST['type_sort'] == '') {
                    unset($_REQUEST['type_sort']);
                } else {
                    $type_sort = $_REQUEST['type_sort'];
                }
            }
            // $sql = "SELECT account_code FROM tbl_account_account WHERE id = '$id_account' ";
            if (isset($id_account) && !empty($id_account)) {
                $result = db_qr($sql);
                $nums = db_nums($result);
                if ($nums > 0) {
                    while ($row = db_assoc($result)) {
                        $customer_introduce = $row['account_code'];
                    }
                }
            }

            if (isset($_REQUEST['id_customer'])) {
                if ($_REQUEST['id_customer'] == '') {
                    unset($_REQUEST['id_customer']);
                } else {
                    $id_customer = $_REQUEST['id_customer'];
                }
            }

            $sql = "SELECT 
                        tbl_customer_customer.customer_fullname,
                        tbl_customer_customer.customer_phone,
                        tbl_customer_customer.customer_introduce,
                        tbl_customer_customer.id as customer_id,
                        tbl_customer_customer.customer_registered as customer_registered,
                        
                        (SELECT MAX(tbl_trading_log.trading_log) FROM tbl_trading_log WHERE tbl_trading_log.id_customer = customer_id) as trading_log,
                        -- (SELECT COUNT(tbl_trading_log.id_customer) FROM tbl_trading_log WHERE tbl_trading_log.id_customer = customer_id) as count,
                        
                        (SELECT COUNT(tbl_trading_log.id_customer) FROM tbl_trading_log WHERE tbl_trading_log.id_customer = customer_id) as total_trade,

                        ROUND((SELECT COUNT(tbl_trading_log.id_customer) FROM tbl_trading_log WHERE trading_result = 'win' AND tbl_trading_log.id_customer = customer_id) / (total_trade)*100) as percent_win,
                        
                        (SELECT SUM(tbl_request_deposit.request_value) FROM tbl_request_deposit WHERE tbl_request_deposit.id_customer = customer_id) as total_money_deposit,
                        (SELECT SUM(tbl_request_payment.request_value) FROM tbl_request_payment WHERE tbl_request_payment.id_customer = customer_id AND  tbl_request_payment.request_status = 3) as total_money_payment
                                    
                        FROM  tbl_customer_customer
                        
                        LEFT JOIN tbl_trading_log
                        ON tbl_customer_customer.id = tbl_trading_log.id_customer
                        
                        LEFT JOIN tbl_account_account 
                        ON tbl_customer_customer.customer_introduce = tbl_account_account.account_code
                        
                        LEFT JOIN tbl_request_deposit 
                        ON tbl_customer_customer.id = tbl_request_deposit.id_customer
                        
                        LEFT JOIN tbl_request_payment 
                        ON tbl_customer_customer.id = tbl_request_payment.id_customer
                                    
                        WHERE  1=1
                    ";

            //////////////////////////////////////////////
            if (!isset($id_account)) {
                $sql_total = "SELECT 
                                COUNT(tbl_customer_customer.id) as total
                                FROM  tbl_customer_customer
                                WHERE  1=1";
            }
            //////////////////////////////////////////////

            if (isset($id_account) && !empty($id_account)) {
                $sql .= " AND tbl_customer_customer.customer_introduce = '$customer_introduce'";
            }

            if (isset($id_customer) && !empty($id_customer)) {
                $sql .= " AND tbl_customer_customer.id = '$id_customer'";
            }

            if (isset($_REQUEST['filter'])) {
                if ($_REQUEST['filter'] == '') {
                    unset($_REQUEST['filter']);
                } else {
                    $filter = htmlspecialchars($_REQUEST['filter']);
                    $sql .= " AND ( tbl_customer_customer.customer_fullname LIKE '%{$filter}%'";
                    $sql .= " OR tbl_customer_customer.customer_phone LIKE '%{$filter}%' )";
                }
            }

            $sql .= " GROUP BY tbl_customer_customer.id";

            echo $sql;
            exit();
            

            if (isset($id_account) && !empty($id_account)) {
                $total = count(db_fetch_array($sql));
            } else {
                $result_total = db_qr($sql_total);
                if (db_nums($result_total) > 0) {
                    while ($row_total = db_assoc($result_total)) {
                        $total = $row_total['total'];
                    }
                }
            }

            $limit = 20;
            $page = 1;

            if (isset($_REQUEST['limit']) && !empty($_REQUEST['limit'])) {
                $limit = $_REQUEST['limit'];
            }
            if (isset($_REQUEST['page']) && !empty($_REQUEST['page'])) {
                $page = $_REQUEST['page'];
            }

            $total_page = ceil($total / $limit);
            $start = ($page - 1) * $limit;

            if (isset($type_sort) && !empty($type_sort)) {
                switch ($type_sort) {
                    case 'desc': {
                            $sql .= " ORDER BY count DESC LIMIT {$start},{$limit}";
                            break;
                        }
                    case 'asc': {
                            $sql .= " ORDER BY count ASC LIMIT {$start},{$limit}";
                            break;
                        }
                }
            } else {
                $sql .= " ORDER BY count DESC LIMIT {$start},{$limit}";
            }
            echo $sql;
            exit();

            $result_arr = array();
            $result_arr['success'] = 'true';
            $result_arr['total'] = strval($total);
            $result_arr['total_page'] = strval($total_page);
            $result_arr['limit'] = strval($limit);
            $result_arr['page'] = strval($page);
            $result_arr['data'] = array();
            $result = db_qr($sql);
            $nums = db_nums($result);
    
            if ($nums > 0) {
                while ($row = db_assoc($result)) {
                    $result_item = array(
                        'id_customer' => (isset($row['customer_id']) && !empty($row['customer_id'])) ? $row['customer_id'] : "",
                        'customer_name' => $row['customer_fullname'],
                        'customer_phone' => $row['customer_phone'],
                        'customer_registered' => $row['customer_registered'],
                        'total_money_deposit' => (!empty($row['total_money_deposit'])) ? $row['total_money_deposit'] : "0",
                        'total_money_payment' => (!empty($row['total_money_payment'])) ? $row['total_money_payment'] : "0",
                        'percent_win' => (isset($row['percent_win']) && !empty($row['percent_win'])) ? $row['percent_win'] : "0",
                        'total_trade' => $row['total_trade'],
                        'trading_log' => (!empty($row['trading_log'])) ? date("d/m/Y", $row['trading_log']) : "",
                    );
                    array_push($result_arr['data'], $result_item);
                }
                reJson($result_arr);
            } else {
                returnError("Danh sách trống");
            }
            break;
        }
    case 'list_customer_history': {
            include_once "./viewlist_board/list_customer_history.php";
            break;
        }
    default:
        returnError("type_manager is not accept!");
        break;
}
