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
    }else{
        returnError("Không tồn tại khách hàng này");
    }

    $sql = "UPDATE tbl_customer_demo SET
        demo_wallet_bet = '$demo_wallet_payment'
        WHERE id = '$id_customer'
        ";
    if (db_qr($sql)) {
        $customer_arr = array();
        $sql = "SELECT * FROM tbl_customer_demo
                WHERE `id` = '{$id_customer}'";
        $result = db_qr($sql);
        $nums = db_nums($result);
        if ($nums > 0) {
            while ($row = db_assoc($result)) {
                $customer_item = array(
                    'id_customer' => $row['id'],
                    'id_bank' => "",
                    'customer_wallet_bet' =>$row['demo_wallet_bet'],
                    'customer_wallet_payment' => "0",
                );
                array_push($customer_arr, $customer_item);
            }
            returnEmptyData("Nạp tiền thành công",$customer_arr);
        } else {
            returnError("Không có khách hàng");
        }   

        // returnEmptyData("Nạp tiền thành công");
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
        $sql_bank = "SELECT *
                        FROM tbl_nasdaqbank_info
                        ";
        $momo_arr = db_fetch_array($sql_momo);
        $momo_random = array_rand($momo_arr);
        $bank_arr = db_fetch_array($sql_bank);
        $bank_random = array_rand($bank_arr);
        $customer_item_momo = array(
            'id_customer' => $row['id'],
            'bank_name' => "",
            'bank_holder' => "",
            'bank_number' => "",
            'customer_name' => $momo_arr[$momo_random]['momo_account'],
            'customer_phone' => $momo_arr[$momo_random]['momo_no'],
            'request_syntax' => "NASDAQNAPTIEN" . $row['customer_phone'],
            'type' => 'momo'
        );
        $customer_item_bank = array(
            'id_customer' => $row['id'],
            'bank_name' => $bank_arr[$bank_random]['bank_name'],
            'bank_holder' => $bank_arr[$bank_random]['bank_holder'],
            'bank_number' => $bank_arr[$bank_random]['bank_number'],
            'customer_name' => "",
            'customer_phone' => "",
            'request_syntax' => $bank_arr[$bank_random]['bank_number'],
            'type' => 'bank'
        );

        array_push($customer_arr['data'], $customer_item_momo);
        array_push($customer_arr['data'], $customer_item_bank);
    }
    reJson($customer_arr);
} else {
    returnError("Không có khách hàng");
}
