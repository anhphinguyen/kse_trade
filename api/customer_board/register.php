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

if ($type_customer == 'trainghiem') {
    $str = "ABCDEFGHIJKLMNOPQRTUVXYZWabcdefghijklmnopqrtuvxyzw1234567890";

    if (isset($_REQUEST['customer_name'])) {
        if ($_REQUEST['customer_name'] == '') {
            unset($_REQUEST['customer_name']);
            $demo_name = substr(str_shuffle($str), -8);
        } else {
            $demo_name = $_REQUEST['customer_name'];
        }
    } else {
        $demo_name = substr(str_shuffle($str), -8);
    }


    $demo_token = md5($demo_name . time());

    $sql = "INSERT INTO tbl_customer_demo SET
            demo_name = '$demo_name',
            demo_token = '$demo_token',
            force_sign_out = '0'
            ";
    if (db_qr($sql)) {
        $id_demo = mysqli_insert_id($conn);
        $sql = "SELECT * FROM tbl_customer_demo WHERE id = '$id_demo'";

        $result_arr = array();
        $result_arr['success'] = "true";
        $result_arr['data'] = array();

        $result = db_qr($sql);
        $nums = db_nums($result);
        if ($nums > 0) {
            while ($row = db_assoc($result)) {
                $result_item = array(
                    'id_customer' => $row['id'],
                    'type_account' => "trainghiem",
                    'customer_name' => $row['demo_name'],
                    'customer_wallet_bet' => $row['demo_wallet_bet'],
                    'customer_token' => $row['demo_token'],
                    'type_customer' => 'trainghiem',
                    'id_bank' => "",
                    'customer_introduce' => "",
                    'customer_code' => "",
                    'customer_phone' => "",
                    'customer_cert_no' => "",
                    'customer_cert_img' => "",
                    'customer_account_no' => "",
                    'customer_account_img' => "",
                    'customer_wallet_payment' => "",
                    'customer_limit_payment' => "",
                );
            }
            array_push($result_arr['data'], $result_item);
        }
        reJson($result_arr);
    } else {
        returnError("Đăng kí tài khoản demo thất bại");
    }
}




if (isset($_REQUEST['customer_introduce']) && !(empty($_REQUEST['customer_introduce']))) {
    if(is_username($customer_introduce)){
        $customer_introduce = $_REQUEST['customer_introduce'];
    }else{
        returnError("Mã giới thiệu không đúng định dạng");
    }
}

if (isset($_REQUEST['customer_phone']) && !(empty($_REQUEST['customer_phone']))) {
    $customer_phone = $_REQUEST['customer_phone'];
} else {
    returnError("Vui lòng nhập số điện thoại");
}

if (isset($_REQUEST['customer_name']) && !(empty($_REQUEST['customer_name']))) {
    $customer_name = $_REQUEST['customer_name'];
} else {
    returnError("Vui lòng điền Họ và Tên");
}


if (isset($_REQUEST['customer_password']) && !(empty($_REQUEST['customer_password']))) {
    if (is_password($_REQUEST['customer_password'])) {
        $customer_password = md5($_REQUEST['customer_password']);
    } else {
        returnError("Mật khẩu không đúng định dạng");
    }
} else {
    returnError("Vui lòng nhập mật khẩu");
}

if (isset($_REQUEST['customer_cert_no']) && !(empty($_REQUEST['customer_cert_no']))) {
    if (is_cert($_REQUEST['customer_cert_no'])) {
        $customer_cert_no = $_REQUEST['customer_cert_no'];
    } else {
        returnError("CMND không đúng định dạng");
    }
} else {
    returnError("Vui lòng nhập CMND");
}

if (isset($_FILES['customer_cert_img'])) { // up product_img
    $customer_cert_img = 'customer_cert_img';
    $dir_save_customer_cert_img = "images/customer_customer/"; // sửa đường dẫn
} else {
    returnError("Vui lòng chụp ảnh CMND mặt trước");
}

$sql = "SELECT * FROM tbl_customer_customer WHERE customer_phone = '$customer_phone'";
$result = db_qr($sql);
if ((db_nums($result)) > 0) {
    returnError("Đã tồn tại tài khoản");
}
$dir_save_cert_img = handing_file_img($customer_cert_img, $dir_save_customer_cert_img);
$customer_code = "KH" . substr(time(), -8);
$customer_token = md5($customer_phone . time());
$sql = "INSERT INTO tbl_customer_customer SET 
        customer_phone = '$customer_phone', 
        customer_token = '$customer_token', 
        customer_fullname = '$customer_name', 
        customer_code = '$customer_code', 
        customer_password = '$customer_password', 
        customer_cert_img = '$dir_save_cert_img', 
        customer_cert_no = '$customer_cert_no'";
if (isset($customer_introduce) && !empty($customer_introduce)) {
    $sql .= ", customer_introduce = '$customer_introduce'";
}


if (db_qr($sql)) {
    $id_demo = mysqli_insert_id($conn);
    $sql = "SELECT * FROM tbl_customer_customer WHERE id = '$id_demo'";

    $result_arr = array();
    $result_arr['success'] = "true";
    $result_arr['data'] = array();

    $result = db_qr($sql);
    $nums = db_nums($result);
    if ($nums > 0) {
        while ($row = db_assoc($result)) {
            $result_item = array(
                'id_customer' => $row['id'],
                'id_bank' => ($row['id_bank'] != 0) ? $row['id_bank'] : "",
                'type_account' => "customer",
                'customer_introduce' => (!empty($row['customer_introduce'])) ? $row['customer_introduce'] : "",
                'customer_code' => $row['customer_code'],
                'customer_phone' => $row['customer_phone'],
                'customer_name' => $row['customer_fullname'],
                'customer_cert_no' => $row['customer_cert_no'],
                'customer_cert_img' => $row['customer_cert_img'],
                'customer_account_no' => (!empty($row['customer_account_no'])) ? $row['customer_account_no'] : "",
                'customer_account_holder' => (!empty($row['customer_account_holder'])) ? $row['customer_account_holder'] : "",
                'customer_account_img' => (!empty($row['customer_account_img'])) ? $row['customer_account_img'] : "",
                'customer_wallet_bet' => $row['customer_wallet_bet'],
                'customer_wallet_payment' => $row['customer_wallet_payment'],
                'customer_limit_payment' => $row['customer_limit_payment'],
                'customer_token' => $row['customer_token'],
                // 'customer_active' => $row['customer_active'],
                'type_customer' => 'customer'
            );
        }
        array_push($result_arr['data'], $result_item);
    }
    reJson($result_arr);
} else {
    returnError("Đăng kí không thành công");
}
