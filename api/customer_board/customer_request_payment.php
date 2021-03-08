<?php

if (isset($_REQUEST['id_customer']) && !empty($_REQUEST['id_customer'])) {
    $id_customer = $_REQUEST['id_customer'];
} else {
    returnError("Nhập id_customer");
}

if (isset($_REQUEST['request_value']) && !empty($_REQUEST['request_value'])) {
    $request_value = $_REQUEST['request_value'];
} else {
    returnError("Nhập request_value");
}

$sql = "SELECT * FROM tbl_customer_customer WHERE id = '$id_customer'";
$result = db_qr($sql);
$nums = db_nums($result);
if ($nums > 0) {
    while ($row = db_assoc($result)) {
        if ($request_value > $row['customer_wallet_pet']) {
            returnError("Số tiền bạn rút vượt quá tài khoản trong ví");
        }

        if ($request_value > $row['customer_limit_payment']) {
            returnError("Bạn đã vượt quá hạn mức giao dịch trong ngày");
        }

        $customer_wallet_pet_update = $row['customer_wallet_pet'] - $request_value;

        $sql = "UPDATE tbl_customer_customer SET 
                customer_wallet_pet = '$customer_wallet_pet_update',
                customer_wallet_payment = '$request_value'
                WHERE id = '$id_customer'
                ";
        if (db_qr($sql)) {
            $request_code = "RT" . substr(time(), -8);
            $sql = "INSERT INTO tbl_request_payment SET
                id_customer = '$id_customer',
                request_code = '$request_code',
                request_value = '$request_value',
                request_status = '1',
                ";
            if (db_qr($sql)) {
                returnSuccess("Gửi yêu cầu rút tiền thành công");
            }
        }
    }
}
