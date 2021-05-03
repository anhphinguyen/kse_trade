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


$sql = "SELECT 
                customer_disable 
                FROM tbl_customer_customer
                WHERE tbl_customer_customer.id = '{$id_customer}'";

$result = db_qr($sql);
if(db_nums($result) > 0){
    while($row = db_assoc($result)){
        if($row['customer_disable'] == 'Y'){
            returnError("Tài khoản đã bị khóa");
        }else{
            returnSuccess("Đăng nhập thành công");
        }
    }
}else{
    returnError("Lỗi truy vấn khóa tài khoản");
}
