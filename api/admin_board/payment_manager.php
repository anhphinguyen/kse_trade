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

            if (isset($_FILES['request_img'])) { // up product_img
                $request_img = 'request_img';
                $dir_save_request_img = "images/request_payment/"; // sửa đường dẫn
            } else {
                returnError("Nhập request_img");
            }

            $dir_save_request = handing_file_img($request_img, $dir_save_request_img);
            $sql = "UPDATE `tbl_request_payment`
                    SET `request_img` = '{$dir_save_request}' 
                    WHERE `id` = '{$id_request}'";
            
            if(db_qr($sql)){
                returnSuccess("Cập nhật thành công");
            }else{
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
