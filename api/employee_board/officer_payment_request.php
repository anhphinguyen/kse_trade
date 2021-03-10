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

    case 'cancel_request_payment': {
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

            if (isset($_REQUEST['request_comment'])) {   //*
                if ($_REQUEST['request_comment'] == '') {
                    unset($_REQUEST['request_comment']);
                    $request_comment = "";
                } else {
                    $request_comment = $_REQUEST['request_comment'];
                }
            } else {
                $request_comment = "";
            }

            $sql = "UPDATE tbl_request_payment SET
                    request_comment = '$request_comment',
                    request_status = '4'
                    WHERE id = '$id_request'
                    ";
            if(db_qr($sql)){
                returnSuccess("Cập nhât thành công");
            }else{
                returnError("Lỗi truy vấn");
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
