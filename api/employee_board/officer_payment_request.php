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
    case 'confirm_request_payment': {
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


            $sql = "SELECT * FROM tbl_request_payment 
                    WHERE id = '$id_request'
                    AND request_status != '1'
                    ";
            $result = db_qr($sql);
            $nums = db_nums($result);
            if ($nums > 0) {
                while ($row = db_assoc($result)) {
                    returnError("Không phải trạng thái tạo lệnh");
                }
            }
            $sql = "UPDATE tbl_request_payment SET
                request_status = '2'
                WHERE id = '$id_request'
                AND request_status = '1'
                ";
            if (db_qr($sql)) {
                returnSuccess("Xác nhận yêu cầu thành công");
            } else {
                returnError("Lỗi truy vấn");
            }
        }
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
            if (db_qr($sql)) {
                $sql = "SELECT id_customer FROM tbl_request_payment WHERE id = '$id_request'";
                $result = db_qr($sql);
                $nums = db_nums($result);
                if ($nums > 0) {
                    while ($row = db_assoc($sql)) {
                        $id_customer = $row['id_customer'];
                        $sql = "SELECT * FROM tbl_customer_customer WHERE id = '$id_customer'";
                        $result = db_qr($sql);
                        $nums = db_nums($result);
                        if ($nums > 0) {
                            while ($row = db_assoc($sql)) {
                                $money_update = $row['customer_wallet_bet'] + $row['customer_wallet_payment'];
                                $sql_update = "UPDATE tbl_customer_customer SET 
                                                customer_wallet_bet = '$money_update',
                                                customer_wallet_payment = '0'
                                                WHERE id = '$id_customer'
                                                ";
                                if (db_qr($sql_update)) {
                                    returnSuccess("Cập nhât thành công");
                                } else {
                                    returnError("Lỗi truy vấn hoàn trả tiền");
                                }
                            }
                        }else{
                            returnError("Lỗi truy vấn tính tiền hoàn trả");
                        }
                    }
                }else{
                    returnError("Lỗi truy vấn get id_customer");
                }
            } else {
                returnError("Lỗi truy vấn hủy yêu cầu");
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
