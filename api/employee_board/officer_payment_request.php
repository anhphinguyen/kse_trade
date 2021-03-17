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
                $title = "Thông báo xác nhận yêu cầu rút tiền từ nhân viên chăm sóc!!!";
                $bodyMessage = "Có thông tin xác nhận yêu cầu khách hàng từ nhân viên chăm sóc!";
                $action = "admin_hasbeen_confirm_request_payment";
                $type_send = 'topic';
                $to = 'KSE_admin_hasbeen_confirm_request_payment';
                pushNotification($title, $bodyMessage, $action, $to, $type_send);
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
                $sql = "SELECT id_customer, request_value FROM tbl_request_payment WHERE id = '$id_request'";
                $result = db_qr($sql);
                $nums = db_nums($result);
                if ($nums > 0) {
                    while ($row = db_assoc($result)) {
                        $request_value = $row['request_value'];
                        echo $request_value;
                        exit();
                        $id_customer = $row['id_customer'];
                        $sql = "SELECT * FROM tbl_customer_customer WHERE id = '$id_customer'";
                        $result = db_qr($sql);
                        $nums = db_nums($result);
                        if ($nums > 0) {
                            while ($row = db_assoc($result)) {
                                $money_bet_update = $row['customer_wallet_bet'] + $request_value;
                                $money_payment_update = $row['customer_wallet_payment'] - $request_value;
                                $sql_update = "UPDATE tbl_customer_customer SET 
                                                customer_wallet_bet = '$money_update',
                                                customer_wallet_payment = '$money_payment_update'
                                                WHERE id = '$id_customer'
                                                ";
                                if (db_qr($sql_update)) {
                                    $title = "Thông báo hủy yêu cầu rút tiền từ quản lý!!!";
                                    $bodyMessage = "Yêu cầu rút tiền của bạn đã bị hủy bỏ!";
                                    $action = "customer_hasbeen_cancel_request_payment";
                                    $type_send = 'topic';
                                    $to = 'KSE_customer_hasbeen_cancel_request_payment';
                                    pushNotification($title, $bodyMessage, $action, $to, $type_send);
                                    returnSuccess("Hủy yêu cầu rút tiền thành công");
                                } else {
                                    returnError("Lỗi truy vấn hoàn trả tiền");
                                }
                            }
                        } else {
                            returnError("Lỗi truy vấn tính tiền hoàn trả");
                        }
                    }
                } else {
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
