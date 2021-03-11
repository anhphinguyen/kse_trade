<?php

if (isset($_REQUEST['type_manager'])) {
    if ($_REQUEST['type_manager'] == '') {
        unset($_REQUEST['type_manager']);
        returnError("Nhập type_manager");
    } else {
        $type_manager = $_REQUEST['type_manager'];
    }
} else {
    returnError("Nhập type_manager");
}

switch ($type_manager) {
    case 'finished': {
            if (isset($_REQUEST['id_support_customer'])) {
                if ($_REQUEST['id_support_customer'] == '') {
                    unset($_REQUEST['id_support_customer']);
                    returnError("Nhập id_support_customer");
                } else {
                    $id_support_customer = $_REQUEST['id_support_customer'];
                }
            } else {
                returnError("Nhập id_support_customer");
            }

            $sql = "SELECT * FROM tbl_support_customer 
                    WHERE id = '$id_support_customer'
                    AND support_status != 'processing'
                    ";
            $result = db_qr($sql);
            $nums = db_nums($result);
            if($nums > 0){
                while($row = db_assoc($result)){
                    returnError("Không phải trạng thái đang xử lý");
                }
            }

            $sql = "UPDATE tbl_support_customer 
                SET support_status = 'finished' 
                WHERE id = '$id_support_customer'
                AND support_status = 'processing'
                ";

            if (db_qr($sql)) {
                returnSuccess("Xử lý thành công");
            }else{
                returnError("Lỗi truy vấn finished");
            }
            break;
        }


    case 'processing': {
            if (isset($_REQUEST['id_support_customer'])) {
                if ($_REQUEST['id_support_customer'] == '') {
                    unset($_REQUEST['id_support_customer']);
                    returnError("Nhập id_support_customer");
                } else {
                    $id_support_customer = $_REQUEST['id_support_customer'];
                }
            } else {
                returnError("Nhập id_support_customer");
            }

            $sql = "SELECT * FROM tbl_support_customer 
                    WHERE id = '$id_support_customer'
                    AND support_status != 'begin'
                    ";
            $result = db_qr($sql);
            $nums = db_nums($result);
            if($nums > 0){
                while($row = db_assoc($result)){
                    returnError("Không phải trạng thái bắt đầu");
                }
            }

            $sql = "UPDATE tbl_support_customer 
                SET support_status = 'processing' 
                WHERE id = '$id_support_customer'
                AND support_status = 'begin'
                ";

            if (db_qr($sql)) {
                returnSuccess("Cập nhật trạng thái đang xử lý thành công");
            }else{
                returnError("Lỗi truy vấn processing");
            }
            break;
        }
    case 'list_support': {
            $sql = "SELECT 
                tbl_support_customer.*,
                tbl_support_category.support_category,
                tbl_support_info.support_request,
                tbl_customer_customer.customer_fullname,
                tbl_customer_customer.customer_phone
                FROM tbl_support_customer
                LEFT JOIN tbl_support_category 
                ON tbl_support_category.id = tbl_support_customer.id_support_category
                LEFT JOIN tbl_support_info 
                ON tbl_support_info.id = tbl_support_customer.id_support_info
                LEFT JOIN tbl_customer_customer 
                ON tbl_customer_customer.id = tbl_support_customer.id_customer
                WHERE tbl_support_customer.support_status < 3
                ";

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
            $sql .= " ORDER BY `tbl_support_customer`.`id` DESC LIMIT {$start},{$limit}";

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
                        'id_support_customer' => $row['id'],
                        'id_customer' => $row['id_customer'],
                        'id_support_category' => $row['id_support_category'],
                        'id_support_info' => $row['id_support_info'],
                        'customer_name' => $row['customer_fullname'],
                        'customer_phone' => $row['customer_phone'],
                        'support_date' => $row['support_date'],
                        'support_request' => $row['support_request'],
                        'support_category' => $row['support_category'],
                        'support_status' => $row['support_status'],
                    );
                    array_push($customer_arr['data'], $customer_item);
                }
                reJson($customer_arr);
            } else {
                returnError("Không có yêu cầu nào");
            }
            break;
        }
    default: {
            returnError("Khong ton tai type_manager");
            break;
        }
}
