<?php

if (isset($_REQUEST['id_customer']) && !empty($_REQUEST['id_customer'])) {
    $id_customer = $_REQUEST['id_customer'];
} else {
    returnError("Nhập id_customer");
}
$sql = "SELECT * FROM tbl_customer_customer WHERE id = '$id_customer'";
$result = db_qr($sql);
$nums = db_nums($result);
$customer_wallet_reward_update = 0;
$customer_wallet_pet_update = 0;
if ($nums > 0) {
    while ($row = db_assoc($result)) {
        $customer_wallet_reward_update = (int)$row['customer_wallet_rewards'];
        $customer_wallet_pet_update = (int)$row['customer_wallet_bet'] + $customer_wallet_reward_update;

        $sql = "UPDATE tbl_customer_customer SET 
                customer_wallet_bet = '$customer_wallet_pet_update',
                customer_wallet_rewards = '0'
                WHERE id = '$id_customer'
                ";
        if (db_qr($sql)) {
            returnSuccess("Quy đổi tiền khuyến mãi thành công!");
        }else{
            returnError("Lỗi truy vấn");
        }
    }
}else{
    returnError("Không tìm thấy thông tin người dùng!");
}

