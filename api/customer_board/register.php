<?php

if(isset($_REQUEST['customer_introduce']) && !(empty($_REQUEST['customer_introduce']))){
    $customer_introduce = $_REQUEST['customer_introduce'];
}

if(isset($_REQUEST['customer_phone']) && !(empty($_REQUEST['customer_phone']))){
    if(is_username($_REQUEST['customer_phone'])){
        $customer_phone = $_REQUEST['customer_phone'];
    }else{
        returnError("customer_phone không đúng định dạng");
    }
}else{
    returnError("Nhập customer_phone");
}

if(isset($_REQUEST['customer_password']) && !(empty($_REQUEST['customer_password']))){
    if(is_password($_REQUEST['customer_password'])){
        $customer_password = md5($_REQUEST['customer_password']);
    }else{
        returnError("customer_password không đúng định dạng");
    }
}else{
    returnError("Nhập customer_password");
}

if(isset($_REQUEST['customer_cert_no']) && !(empty($_REQUEST['customer_cert_no']))){
    if(is_cert($_REQUEST['customer_cert_no'])){
        $customer_cert_no = $_REQUEST['customer_cert_no'];
    }else{
        returnError("customer_cert_no không đúng định dạng");
    }
}else{
    returnError("Nhập customer_cert_no");
}

if (isset($_FILES['customer_cert_img'])) { // up product_img
    $customer_cert_img = 'customer_cert_img';
    $dir_save_customer_cert_img = "images/customer_cert_img/"; // sửa đường dẫn
} else {
    returnError("Nhập customer_cert_img");
}



$sql = "SELECT * FROM tbl_customer_customer WHERE customer_phone = '$customer_phone'";
$result = db_qr($sql);
if((db_nums($result)) > 0){
    returnError("Đã tồn tại tài khoản");
}
$dir_save_cert_img = handing_file_img($customer_cert_img, $dir_save_customer_cert_img);
$customer_code ="KH" . substr(time(), -8);
$sql = "INSERT INTO tbl_customer_customer SET 
        customer_phone = '$customer_phone', 
        customer_code = '$customer_code', 
        customer_password = '$customer_password', 
        customer_introduce = '$customer_introduce', 
        customer_cert_img = '$dir_save_cert_img', 
        customer_cert_no = '$customer_cert_no'";

if(db_qr($sql)){
   returnSuccess("Đăng kí tài khoản thành công");
}