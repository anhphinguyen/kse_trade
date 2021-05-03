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

switch($type_customer){
    case 'customer':{
        $sql = "SELECT 
                tbl_customer_customer.id as id,
                tbl_customer_customer.customer_wallet_bet as customer_wallet_bet,
                tbl_customer_customer.customer_wallet_payment as customer_wallet_payment,
                tbl_customer_customer.customer_wallet_rewards as customer_wallet_rewards,
                tbl_bank_info.id as id_bank 
                FROM tbl_customer_customer
                LEFT JOIN tbl_bank_info ON tbl_customer_customer.id_bank = tbl_bank_info.id
                WHERE tbl_customer_customer.id = '{$id_customer}'";
        break;
    }
    case 'trainghiem':{
        $sql = "SELECT * FROM tbl_customer_demo
                WHERE `id` = '{$id_customer}'";
        break;
    }
}

$customer_arr = array();
$customer_arr['success'] = 'true';

$customer_arr['data'] = array();
$result = db_qr($sql);
$nums = db_nums($result);


if ($nums > 0) {
    while ($row = db_assoc($result)) {
        $customer_item = array(
            'id_customer' => $row['id'],
            'id_bank' => (isset($row['id_bank']) && !empty($row['id_bank']))?$row['id_bank']:"",
            'customer_wallet_bet' => htmlspecialchars_decode((isset($row['customer_wallet_bet']))?$row['customer_wallet_bet']:$row['demo_wallet_bet']),
            'customer_wallet_payment' => htmlspecialchars_decode((isset($row['customer_wallet_payment']) && !empty($row['customer_wallet_payment']))?$row['customer_wallet_payment']:"0"),
            'customer_wallet_rewards' => htmlspecialchars_decode((isset($row['customer_wallet_rewards']) && !empty($row['customer_wallet_rewards']))?$row['customer_wallet_rewards']:"0")
        );
        array_push($customer_arr['data'], $customer_item);
    }
    reJson($customer_arr);
} else {
    returnError("Không có khách hàng");
}
