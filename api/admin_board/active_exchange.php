<?php
$sql = "UPDATE tbl_exchange_exchange 
SET exchange_active = 'N'";
if (db_qr($sql)) {
    $sql = "UPDATE tbl_customer_customer 
        SET customer_active = 'N'";
    if (db_qr($sql)) {
        $sql = "UPDATE tbl_customer_demo 
            SET demo_active = 'N'";
        if (db_qr($sql)) {
            returnSuccess("Mở lại sàn giao dịch thành công");
        }
    }
}
