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
    case 'request_support': {
            if (isset($_REQUEST['id_customer'])) {
                if ($_REQUEST['id_customer'] == '') {
                    unset($_REQUEST['id_customer']);
                    returnError("Nhập id_customer");
                } else {
                    $id_customer = $_REQUEST['id_customer'];
                }
            } else {
                returnError("Nhập id_customer");
            }

            if (isset($_REQUEST['id_support_category'])) {
                if ($_REQUEST['id_support_category'] == '') {
                    unset($_REQUEST['id_support_category']);
                    returnError("Nhập id_support_category");
                } else {
                    $id_support_category = $_REQUEST['id_support_category'];
                }
            } else {
                returnError("Nhập id_support_category");
            }

            if (isset($_REQUEST['support_request'])) {
                if ($_REQUEST['support_request'] == '') {
                    unset($_REQUEST['support_request']);
                } else {
                    $support_request = htmlspecialchars($_REQUEST['support_request']);
					
                }
            }

            $sql = "INSERT INTO tbl_support_info SET 
                id_category = '$id_support_category'
                ";
                
            if (isset($support_request) && !empty($support_request)) {
                $sql .= ", support_request = '".mysqli_escape_string($conn,$support_request)."'";
            }

            if (db_qr($sql)) {
                $id_support_info = mysqli_insert_id($conn);
                $support_date = time();
                $sql = "INSERT INTO tbl_support_customer SET 
                    id_customer = '$id_customer',
                    id_support_category = '$id_support_category',
                    id_support_info = '$id_support_info',
                    support_date = '$support_date'
                    ";
                if (db_qr($sql)) {
                    $title = "Thông báo có yêu cầu hỗ trợ!!!";
                    $bodyMessage = "Có yêu cầu hỗ trợ từ khách hàng!";
                    $action = "officer_support";
                    $type_send = 'topic';
                    $to = 'KSE_officer_support';
                    pushNotification($title, $bodyMessage, $action, $to, $type_send);
                    returnSuccess("Gửi yêu cầu thành công");
                } else {
                    returnError("Lỗi truy vấn");
                }
            }



            break;
        }
    case 'list_support_category': {
            $customer_arr = array();
            $sql = "SELECT * FROM tbl_support_category";
            $result = db_qr($sql);
            $nums = db_nums($result);
            if ($nums > 0) {
                $customer_arr['success'] = 'true';
                $customer_arr['data'] = array();
                while ($row = db_assoc($result)) {
                    $customer_item = array(
                        'id_support_category' => $row['id'],
                        'support_category' => htmlspecialchars_decode($row['support_category']),
                    );

                    array_push($customer_arr['data'], $customer_item);
                }
                reJson($customer_arr);
            } else {
                returnError("Không tìm thấy yêu cầu");
            }
            break;
        }
    default: {
            returnError("Khong ton tai type_manager");
            break;
        }
}
