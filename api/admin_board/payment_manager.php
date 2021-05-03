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

    case 'update_img_payment': {
            if (isset($_REQUEST['id_request'])) {   //*
                if ($_REQUEST['id_request'] == '') {
                    unset($_REQUEST['id_request']);
                    returnError("Nhap id_request");
                } else {
                    $id_request = $_REQUEST['id_request'];
                }
            } else {
                returnError("Nhap id_request");
            }

            if (isset($_REQUEST['id_customer'])) {   //*
                if ($_REQUEST['id_customer'] == '') {
                    unset($_REQUEST['id_customer']);
                    returnError("Nhap id_customer");
                } else {
                    $id_customer = $_REQUEST['id_customer'];
                }
            } else {
                returnError("Nhap id_customer");
            }

            $sql = "SELECT * FROM tbl_request_payment 
                    WHERE id = '$id_request'
                    AND request_status != '2'
                    ";
            $result = db_qr($sql);
            $nums = db_nums($result);
            if ($nums > 0) {
                returnError("Không phải trạng thái chờ xử lý");
            }

            if (isset($_FILES['request_img'])) { // up product_img
                $request_img = 'request_img';
                $dir_save_request_img = "images/request_payment/"; // sửa đường dẫn
            } else {
                returnError("Nhập request_img");
            }

            $dir_save_request = handing_file_img($request_img, $dir_save_request_img);
            $sql = "UPDATE `tbl_request_payment`
                    SET `request_img` = '{$dir_save_request}',
                        `request_completed` = '".time()."',
                        `request_status` = '3'
                    WHERE `id` = '{$id_request}'";

            if (db_qr($sql)) {
                $sql = "SELECT request_value FROM tbl_request_payment WHERE id = '$id_request'";
                $result = db_qr($sql);
                if(db_nums($result) > 0){
                    while($row = db_assoc($result)){
                        $request_value = $row['request_value'];
                    }
                }

                $sql = "SELECT customer_wallet_payment FROM tbl_customer_customer WHERE id = '$id_customer'";
                $result = db_qr($sql);
                if(db_nums($result) > 0){
                    while($row = db_assoc($result)){
                        $customer_wallet_payment_update = strval((int)$row['customer_wallet_payment'] - (int)$request_value);
                    }
                }
                $sql = "UPDATE tbl_customer_customer SET customer_wallet_payment = '$customer_wallet_payment_update' WHERE id = '$id_customer'";
                if (db_qr($sql)) {
                    $title = "Thông báo xác nhận yêu cầu rút tiền!!!";
                    $bodyMessage = "Yêu cầu rút tiền của bạn đã được xác nhận!";
                    $action = "customer_hasbeen_confirm_request_payment";
                    $type_send = 'topic';
                    $to = 'KSE_customer_hasbeen_confirm_request_payment_'.strval($id_customer);
                    pushNotification($title, $bodyMessage, $action, $to, $type_send);
                    returnSuccess("Cập nhật thành công");
                } else {
                    returnError("Lỗi truy vấn hoàn thành rút tiền");
                }
            } else {
                returnError("Lỗi truy vấn");
            }
        }
    case 'list_payment_detail': {
            include_once "./viewlist_board/list_payment_detail.php";
        }
    case 'list_request_payment': {
            include_once "./viewlist_board/list_request_payment.php";
        }
    default:
        returnError("type_manager is not accept!");
        break;
}
