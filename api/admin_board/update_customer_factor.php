<?php
$reset_factor = "";
if (isset($_REQUEST['reset_factor']) && !empty($_REQUEST['reset_factor'])) {
    $reset_factor = $_REQUEST['reset_factor'];
}

if (!empty($reset_factor) && $reset_factor == 'Y') {
    $sql = "UPDATE tbl_customer_customer SET 
            customer_factor = '1'
            WHERE 1
            ";
    db_qr($sql);
    returnSuccess("Reset thành công");
} else {
    if (isset($_REQUEST['id_customer_str']) && !empty($_REQUEST['id_customer_str'])) {
        $id_customer_arr = explode(',', $_REQUEST['id_customer_str']);
    } else {
        returnError("Nhập id_customer_str");
    }

    $customer_factor = "1";
    if (isset($_REQUEST['customer_factor']) && !empty($_REQUEST['customer_factor'])) {
        $customer_factor = $_REQUEST['customer_factor'];
    }

    foreach ($id_customer_arr as $id_customer) {
        $sql = "UPDATE tbl_customer_customer SET 
                customer_factor = '$customer_factor'
                WHERE id = '$id_customer'
                ";
        db_qr($sql);
    }
    returnSuccess("Cập nhật thành công");
}
