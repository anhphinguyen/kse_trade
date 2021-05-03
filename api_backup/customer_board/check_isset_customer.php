<?php

if(isset($_REQUEST['customer_phone']) && !(empty($_REQUEST['customer_phone']))){
    $customer_phone = $_REQUEST['customer_phone'];
}else{
    returnError("Nhập customer_phone");
}

$sql = "SELECT * FROM tbl_customer_customer WHERE customer_phone = '$customer_phone'";
$result = db_qr($sql);
if((db_nums($result)) > 0){
    returnSuccess("Đã tồn tại khách hàng này");
}else{
    returnError("Khách hàng này chưa tồn tại");
}