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
            returnSuccess("Cập nhật thành công");
        }
    }
}
