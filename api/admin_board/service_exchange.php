<?php
if (isset($_REQUEST['exchange_active'])) {
    if ($_REQUEST['exchange_active'] == '') {
        unset($_REQUEST['exchange_active']);
        returnError("Nhập exchange_active");
    } else {
        $exchange_active = $_REQUEST['exchange_active'];
    }
} else {
    returnError("Nhập exchange_active");
}

$sql = "UPDATE tbl_exchange_exchange 
        SET exchange_active = '$exchange_active'";
if (db_qr($sql)) {
    $sql = "UPDATE tbl_customer_customer 
        SET customer_active = '$exchange_active'";
    if (db_qr($sql)) {
        $sql = "UPDATE tbl_customer_demo 
            SET demo_active = '$exchange_active'";
        if (db_qr($sql)) {
            $title = "Thông báo bảo trì!!!";
            if($exchange_active == 'N'){
                $bodyMessage = "Sàn giao dịch đã bảo trì, vui lòng quay lại sau!";
            }else{
                $bodyMessage = "Sàn giao dịch đã hoàn tất bảo trì!";
            }
            $action = "service_exchange";
            $type_send = 'topic';
            
            pushNotification($title, $bodyMessage, $action, $to, $type_send);
            returnSuccess("Cập nhật thành công");
        }
    }
}
