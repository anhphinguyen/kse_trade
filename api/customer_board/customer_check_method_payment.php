<?php

if (isset($_REQUEST['id_customer']) && !empty($_REQUEST['id_customer'])) {
    $id_customer = $_REQUEST['id_customer'];
} else {
    returnError("Nhập id_customer");
}

$sql = "SELECT * FROM tbl_customer_customer WHERE id = '$id_customer'";
$result = db_qr($sql);
$nums = db_nums($result);
if ($nums > 0) {
    while ($row = db_assoc($result)) {
        if($row['id_bank'] == 0){
            returnError("Quy khách chưa tạo phương thức thanh toán. Xin vui lòng liên kết đến hạng mục phương thức thanh toán để hoàn thành !");
        }else{
            returnSuccess("Quý khách đã tạo phương thức thanh toán");
        }
    }
}