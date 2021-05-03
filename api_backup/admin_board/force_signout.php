<?php

if (isset($_REQUEST['target'])) {
    if ($_REQUEST['target'] == '') {
        unset($_REQUEST['target']);
    }
}

if (! isset($_REQUEST['target'])) {
    returnError("target is missing!");
}

$target = $_REQUEST['target'];

//push notity admin
$title = "Thông báo đăng nhập!!!";
$bodyMessage = "Phiên làm việc đã kết thúc, vui lòng đăng nhập lại để tiếp tục.";
$action = "check_sign_out";
$type_send = 'topic';
$to = 'KSE_notification';
switch ($target) {
    case 'admin':
        $to = "KSE_notification_customer";
        
        $query = "UPDATE tbl_account_account SET ";
        $query .= " force_sign_out  = '1'  WHERE id_type = '1'";
        $conn->query($query);
        
        break;
    case 'employee':
        $to = "KSE_notification_employee";
        
        $query = "UPDATE tbl_account_account SET ";
        $query .= " force_sign_out  = '1' WHERE id_type != '1'";
        $conn->query($query);
        
        break;
}

pushNotification($title, $bodyMessage, $action, $to, $type_send);
returnSuccess("Gửi thông báo thành công!");