<?php
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

if (isset($_REQUEST['id_account'])) {
    if ($_REQUEST['id_account'] == '') {
        unset($_REQUEST['id_account']);
        returnError("Nhập id_account");
    } else {
        $id_account = $_REQUEST['id_account'];
    }
} else {
    returnError("Nhập id_account");
}

if (isset($_REQUEST['money_withdraw'])) {
    if ($_REQUEST['money_withdraw'] == '') {
        unset($_REQUEST['money_withdraw']);
        returnError("Nhập money_withdraw");
    } else {
        $money_withdraw = $_REQUEST['money_withdraw'];
    }
} else {
    returnError("Nhập money_withdraw");
}

$request_type = '1';
if (isset($_REQUEST['request_type']) && !(empty($_REQUEST['request_type']))) {
    $request_type = $_REQUEST['request_type'];
}


$sql = "SELECT customer_wallet_bet,customer_wallet_rewards FROM tbl_customer_customer
        WHERE id = '$id_customer'
        ";
$result = db_qr($sql);
$customer_wallet_bet_update = 0;
$sql2 = "";
if (db_nums($result) > 0) {
    while ($row = db_assoc($result)) {
        if($request_type == '1'){
            $customer_wallet_bet_update = $row['customer_wallet_bet'] - $money_withdraw;
            $sql2 = "UPDATE tbl_customer_customer 
                        SET customer_wallet_bet = '$customer_wallet_bet_update' , `id_account` = '{$id_account}'
                        WHERE id = '$id_customer'";
        }else{
            $customer_wallet_bet_update = $row['customer_wallet_rewards'] - $money_withdraw;
            $sql2 = "UPDATE tbl_customer_customer 
                        SET customer_wallet_rewards = '$customer_wallet_bet_update' , `id_account` = '{$id_account}'
                        WHERE id = '$id_customer'";
        }
        
    }
}

// $sql = "UPDATE `tbl_customer_customer` SET";
// $sql .= " `customer_wallet_bet` = '{$customer_wallet_bet_update}'";
// $sql .= ", `id_account` = '{$id_account}'";
// $sql .= " WHERE `id` = '{$id_customer}'";

if (db_qr($sql2)) {
    returnSuccess("Cập nhật thành công");
} else {
    returnError("Lỗi truy vấn");
}
