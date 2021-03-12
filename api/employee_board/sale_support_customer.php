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
    case 'list_customer': {
            if (isset($_REQUEST['type_sort'])) {
                if ($_REQUEST['type_sort'] == '') {
                    unset($_REQUEST['type_sort']);
                } else {
                    $type_sort = $_REQUEST['type_sort'];
                }
            }

            $sql = "SELECT 
                    tbl_customer_customer.customer_fullname,
                    tbl_customer_customer.customer_phone,
                    tbl_customer_customer.customer_introduce,

                    tbl_trading_log.id_customer as customer_id,
                    tbl_trading_log.trading_type as trading_type,
                    MAX(tbl_trading_log.trading_log) as trading_log,
                    COUNT(tbl_trading_log.id_customer) as count,
                    (SELECT COUNT(tbl_trading_log.id_customer) FROM tbl_trading_log WHERE tbl_trading_log.id_customer = customer_id) as total_trade,
                    ROUND((SELECT COUNT(tbl_trading_log.id_customer) FROM tbl_trading_log WHERE trading_result = 'win' AND tbl_trading_log.id_customer = customer_id) / (SELECT COUNT(tbl_trading_log.id_customer) FROM tbl_trading_log WHERE tbl_trading_log.id_customer = customer_id)*100) as percent_win

                    FROM tbl_trading_log
                    LEFT JOIN tbl_customer_customer
                    ON tbl_customer_customer.id = tbl_trading_log.id_customer
                    LEFT JOIN tbl_account_account
                    ON tbl_customer_customer.customer_introduce = tbl_account_account.account_code
                    WHERE 1=1
                    ";
            if (isset($_REQUEST['filter'])) {
                if ($_REQUEST['filter'] == '') {
                    unset($_REQUEST['filter']);
                } else {
                    $filter = htmlspecialchars($_REQUEST['filter']);
                    $sql .= " AND ( tbl_customer_customer.customer_fullname LIKE '%{$filter}%'";
                    $sql .= " OR tbl_customer_customer.customer_phone LIKE '%{$filter}%' )";
                }
            }
            $sql .= " GROUP BY tbl_trading_log.id_customer";
            $result_arr = array();

            $total = count(db_fetch_array($sql));
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
            
            $result_arr['success'] = 'true';

            $result_arr['total'] = strval($total);
            $result_arr['total_page'] = strval($total_page);
            $result_arr['limit'] = strval($limit);
            $result_arr['page'] = strval($page);
            $result_arr['data'] = array();
            $result = db_qr($sql);
            $nums = db_nums($result);
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
            }
            $result_arr['success'] = "true";
            $result_arr['total'] = strval($total);
            $result_arr['data'] = array();

            $result = db_qr($sql);
            $nums = db_nums($result);
            if ($nums > 0) {
                while ($row = db_assoc($result)) {
                    $result_item = array(
                        'id_customer' => $row['customer_id'],
                        'customer_name' => $row['customer_fullname'],
                        'customer_phone' => $row['customer_phone'],
                        'percent_win' => $row['percent_win'],
                        'total_trade' => $row['total_trade'],
                        'trading_log' => date("d/m/Y", $row['trading_log']),
                    );
                    array_push($result_arr['data'], $result_item);
                }
                reJson($result_arr);
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
