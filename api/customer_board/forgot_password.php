<?php

if(isset($_REQUEST['id_customer']) && !(empty($_REQUEST['id_customer']))){
        $id_customer = $_REQUEST['id_customer'];
}else{
    returnError("Nhập id_customer");
}

if(isset($_REQUEST['new_pass']) && !(empty($_REQUEST['new_pass']))){
    if(is_password($_REQUEST['new_pass'])){
        $new_pass = md5($_REQUEST['new_pass']);
    }else{
        returnError("new_pass không đúng định dạng");
    }
}else{
    returnError("Nhập new_pass");
}

$sql = "UPDATE tbl_customer_customer SET customer_password = '$new_pass' WHERE id = '$id_customer'";
if(db_qr($sql)){
    returnSuccess("Cập nhật thành công");
}else{
    returnError("Cập nhật thất bại");
}