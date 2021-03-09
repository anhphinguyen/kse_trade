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

    case 'create_request_comfirm': {
            if (isset($_REQUEST['id_customer']) && !(empty($_REQUEST['id_customer']))) {
                $id_customer = $_REQUEST['id_customer'];
            } else {
                returnError("Nhập id_customer");
            }

            if (isset($_REQUEST['request_value']) && !(empty($_REQUEST['request_value']))) {
                $request_value = $_REQUEST['request_value'];
            } else {
                returnError("Nhập request_value");
            }

            $request_code = "NT" . substr(time(), -8);
            $request_time_completed = time();
            $sql = "INSERT INTO tbl_request_deposit SET
                    id_customer = '$id_customer',
                    request_value = '$request_value',
                    request_time_completed = '$request_time_completed',
                    request_code = '$request_code'
                    ";
            if (db_qr($sql)) {
                $sql = "SELECT customer_wallet_bet FROM tbl_customer_customer WHERE id = '$id_customer'";
                $result = db_qr($sql);
                $nums = db_nums($result);
                if($nums > 0){
                    while($row = db_assoc($result)){
                        $customer_wallet_update = (int)$row['customer_wallet_bet'] + $request_value;
                    }
                }
                $sql = "UPDATE tbl_customer_customer 
                        SET customer_wallet_bet = '$customer_wallet_update' 
                        WHERE id = '$id_customer'";
                if (db_qr($sql)) {
                    returnSuccess("Tạo lệnh nạp tiền thành công");
                }
            }
            break;
        }
    case 'list_customer': {
            $sql = "SELECT * FROM `tbl_customer_customer` WHERE 1=1";

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


            $customer_arr = array();

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
            $sql .= " ORDER BY `tbl_customer_customer`.`id` DESC LIMIT {$start},{$limit}";

            if (empty($error)) {
                $customer_arr['success'] = 'true';

                $customer_arr['total'] = strval($total);
                $customer_arr['total_page'] = strval($total_page);
                $customer_arr['limit'] = strval($limit);
                $customer_arr['page'] = strval($page);
                $customer_arr['data'] = array();
                $result = db_qr($sql);
                $nums = db_nums($result);

                if ($nums > 0) {
                    while ($row = db_assoc($result)) {
                        $customer_item = array(
                            'id_customer' => $row['id'],
                            'customer_name' => htmlspecialchars_decode($row['customer_fullname']),
                            'customer_phone' => htmlspecialchars_decode($row['customer_phone']),
                            'customer_cert_no' => htmlspecialchars_decode($row['customer_cert_no']),
                        );
                        array_push($customer_arr['data'], $customer_item);
                    }
                    reJson($customer_arr);
                } else {
                    returnSuccess("Không có khách hàng");
                }
            }

            break;
        }
    case 'list_deposit_detail': {
            include_once "./viewlist_board/list_deposit_detail.php";
        }
    case 'list_request_deposit': {
            include_once "./viewlist_board/list_request_deposit.php";
        }
    default:
        returnError("type_manager is not accept!");
        break;
}
