<?php
if (isset($_REQUEST['type_customer'])) {
    if ($_REQUEST['type_customer'] == '') {
        unset($_REQUEST['type_customer']);
        returnError("Nhập type_customer");
    } else {
        $type_customer = $_REQUEST['type_customer'];
    }
} else {
    returnError("Nhập type_customer");
}

if (isset($_REQUEST['id_customer'])) {
    if ($_REQUEST['id_customer'] == '') {
        unset($_REQUEST['id_customer']);
        returnError("Nhập id_customer");
    } else {
        $id_customer = $_REQUEST['id_customer'];
    }
} else {
    returnError("Nhập id_customer");
}

if ($type_customer === 'trainghiem') {
    $sql = "SELECT demo_wallet_payment FROM tbl_customer_demo WHERE id = '$id_customer'";
    $result = db_qr($sql);
    $nums = db_nums($result);
    if ($nums > 0) {
        while ($row = db_assoc($result)) {
            $demo_wallet_payment = $row['demo_wallet_payment']; // value default is 100.000
        }
    }

    $sql = "UPDATE tbl_customer_demo SET
        demo_wallet_bet = '$demo_wallet_payment'
        WHERE id = '$id_customer'
        ";
    if (db_qr($sql)) {
        returnEmptyData("Nạp tiền thành công");
    } else {
        returnError("Lỗi truy vấn");
    }
}

$sql = "SELECT * FROM `tbl_customer_customer` WHERE `id` = '{$id_customer}'";

$customer_arr = array();
$customer_arr['success'] = 'true';

$customer_arr['data'] = array();
$result = db_qr($sql);
$nums = db_nums($result);

if ($nums > 0) {
    while ($row = db_assoc($result)) {

        $sql_momo = "SELECT *
                        FROM tbl_momo_info
                        ";
        $momo_arr = db_fetch_array($sql_momo);
        $momo_random = array_rand($momo_arr);
        $customer_item = array(
            'id_customer' => $row['id'],
            'customer_name' => $momo_arr[$momo_random]['momo_account'],
            'customer_phone' => $momo_arr[$momo_random]['momo_no'],
            'request_syntax' => "KSENAPTIEN" . $row['customer_phone']
        );

        array_push($customer_arr['data'], $customer_item);
    }
    reJson($customer_arr);
} else {
    returnError("Không có khách hàng");
}
