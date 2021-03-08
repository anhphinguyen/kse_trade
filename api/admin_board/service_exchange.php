<?php

$sql = "UPDATE tbl_exchange_exchange 
        SET exchange_active = 'Y'";
if (db_qr($sql)) {
    $sql = "UPDATE tbl_customer_customer 
        SET customer_active = 'Y'";
    if (db_qr($sql)) {
        $sql = "UPDATE tbl_customer_demo 
            SET demo_active = 'Y'";
        if (db_qr($sql)) {
            returnSuccess("Bảo trì sàn giao dịch thành công");
        }
    }
}
