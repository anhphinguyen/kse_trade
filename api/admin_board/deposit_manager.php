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

            $request_type = '1';
            if (isset($_REQUEST['request_type']) && !(empty($_REQUEST['request_type']))) {
                $request_type = $_REQUEST['request_type'];
            }
            // flag invest
            $invest = 'N';
            if (isset($_REQUEST['invest']) && !(empty($_REQUEST['invest']))) {
                $invest = $_REQUEST['invest'];
            }

            if (isset($_REQUEST['request_value']) && !(empty($_REQUEST['request_value']))) {
                $request_value = $_REQUEST['request_value'];
                if (!is_numeric($request_value)) {
                    returnError("Sai định dạng request_value");
                }
                if (strpos($request_value, '.') !== false)
                    $request_value = str_replace(".", "", $request_value);

                if (strpos($request_value, ',') !== false)
                    $request_value = str_replace(",", "", $request_value);
            } else {
                if ($request_type == '2')
                    returnError("Nhập request_value");
                else {
                    $request_value = '0';
                }
            }

            $request_bonus = '0';
            if (isset($_REQUEST['request_bonus']) && !(empty($_REQUEST['request_bonus']))) {
                $request_bonus = $_REQUEST['request_bonus'];
            }

            if ($request_value == '0' && $request_bonus == '0')
                returnError("Nhập số tiền nạp");

            $request_code = "NT" . substr(time(), -8);
            $request_time_completed = time();
            if ($request_value != '0') {

                $sql = "INSERT INTO tbl_request_deposit SET
                    id_customer = '$id_customer',
                    request_value = '$request_value',
                    request_time_completed = '$request_time_completed',
                    request_type = '$request_type',
                    request_code = '$request_code'
                    ";
                if (db_qr($sql)) {

                    $sql = "SELECT customer_wallet_bet,customer_wallet_rewards FROM tbl_customer_customer WHERE id = '$id_customer'";
                    $result = db_qr($sql);
                    $nums = db_nums($result);
                    $customer_wallet_update = 0;
                    $sql = "";
                    if ($nums > 0) {
                        while ($row = db_assoc($result)) {
                            if ($request_type == '1') {
                                $customer_wallet_update = (int)$row['customer_wallet_bet'] + $request_value + (int)$request_bonus;
                                $sql = "UPDATE tbl_customer_customer 
                                                SET customer_wallet_bet = '$customer_wallet_update' 
                                                WHERE id = '$id_customer'";

                                // if ($invest == 'Y') {
                                //     $sql_insert_bonus = "INSERT INTO tbl_request_bonus SET
                                //                     request_value = '$request_value',
                                //                     request_time_completed = '$request_time_completed',
                                //                     request_code = '$request_code',
                                //                     request_type = '2',
                                //                     id_customer = '$id_customer'
                                //                     ";
                                //     db_qr($sql_insert_bonus);
                                // }
                            } else {
                                $customer_wallet_update = (int)$row['customer_wallet_rewards'] + $request_value;
                                $sql = "UPDATE tbl_customer_customer 
                                                SET customer_wallet_rewards = '$customer_wallet_update' 
                                                WHERE id = '$id_customer'";
                            }
                        }
                    }

                    if (db_qr($sql)) {
                        if ($request_bonus != '0' && $request_type == '1') {
                            $sql_get_id_account_by_customer = "SELECT tbl_account_account.id AS id_account
                                FROM tbl_customer_customer 
                                    LEFT JOIN tbl_account_account ON tbl_account_account.account_code = tbl_customer_customer.customer_introduce
                                    WHERE tbl_customer_customer.id = '$id_customer'
                                ";
                            $result_get_id_account_by_customer = db_qr($sql_get_id_account_by_customer);
                            $nums_result_get_id_account_by_customer = db_nums($result_get_id_account_by_customer);

                            $id_account = '0';
                            if ($nums_result_get_id_account_by_customer > 0) {
                                while ($row_get_id_account_by_customer = db_assoc($result_get_id_account_by_customer)) {
                                    $id_account = $row_get_id_account_by_customer['id_account'];
                                }
                            }


                            $sql_insert_bonus = "INSERT INTO tbl_request_bonus SET
                                id_customer = '$id_customer',
                                request_value = '$request_bonus',
                                id_account = '$id_account',
                                request_time_completed = '$request_time_completed',
                                request_code = '$request_code'
                                ";
                            db_qr($sql_insert_bonus);
                        }

                        returnSuccess("Tạo lệnh nạp tiền thành công");
                    } else {
                        returnError("Lỗi truy vấn");
                    }
                }
            } else {
                if ($invest == 'Y') {
                    $sql = "SELECT customer_wallet_bet FROM tbl_customer_customer WHERE id = '$id_customer'";
                    $result = db_qr($sql);
                    $nums = db_nums($result);
                    $customer_wallet_update = 0;
                    $sql = "";
                    if ($nums > 0) {
                        while ($row = db_assoc($result)) {
                            if ($request_type == '1') {
                                $customer_wallet_update = (int)$row['customer_wallet_bet'] + $request_value + (int)$request_bonus;
                                $sql = "UPDATE tbl_customer_customer 
                                                SET customer_wallet_bet = '$customer_wallet_update' 
                                                WHERE id = '$id_customer'";

                                if ($invest == 'Y') {
                                    $sql_insert_bonus = "INSERT INTO tbl_request_bonus SET
                                                    request_value = '$request_bonus',
                                                    request_time_completed = '$request_time_completed',
                                                    request_code = '$request_code',
                                                    request_type = '2',
                                                    id_customer = '$id_customer'
                                                    ";
                                    db_qr($sql_insert_bonus);
                                }
                                if (db_qr($sql)) {
                                    returnSuccess("Tạo lệnh nạp tiền thành công");
                                }
                            }
                        }
                    }else{
                        returnError("Không tìm thấy người chơi");
                    }

                    

                }else{
                    $sql = "SELECT customer_wallet_bet,customer_wallet_rewards FROM tbl_customer_customer WHERE id = '$id_customer'";
                    $result = db_qr($sql);
                    $nums = db_nums($result);
                    $customer_wallet_update = 0;
                    if ($nums > 0) {
                        while ($row = db_assoc($result)) {
                            $customer_wallet_update = (int)$row['customer_wallet_bet'] + (int)$request_bonus;
                            $sql = "UPDATE tbl_customer_customer 
                                                    SET customer_wallet_bet = '$customer_wallet_update' 
                                                    WHERE id = '$id_customer'";
                        }
                    }
                    if (db_qr($sql)) {
                        returnSuccess("Tạo lệnh nạp tiền thành công");
                    } else {
                        returnError("Lỗi truy vấn");
                    }
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
                    returnError("Không có khách hàng");
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
