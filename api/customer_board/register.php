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

if ($type_customer == 'demo') {
    $str = "ABCDEFGHIJKLMNOPQRTUVXYZWabcdefghijklmnopqrtuvxyzw1234567890";

    if (isset($_REQUEST['demo_name'])) {
        if ($_REQUEST['demo_name'] == '') {
            unset($_REQUEST['demo_name']);
            $demo_name = substr(str_shuffle($str), -8);
        } else {
            $demo_name = $_REQUEST['demo_name'];
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
        $result_arrp['success'] = "true";
        $result_arr['data'] = array();

        $result = db_qr($sql);
        $nums = db_nums($result);
        if ($nums > 0) {
            while ($row = db_assoc($result)) {
                $result_item = array(
                    'id_demo' => $row['id'],
                    'demo_name' => $row['demo_name'],
                    'demo_wallet_bet' => $row['demo_wallet_bet'],
                    'demo_token' => $row['demo_token'],
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
    $customer_introduce = $_REQUEST['customer_introduce'];
}

if (isset($_REQUEST['customer_phone']) && !(empty($_REQUEST['customer_phone']))) {
    $customer_phone = $_REQUEST['customer_phone'];
} else {
    returnError("Nhập customer_phone");
}

if (isset($_REQUEST['customer_name']) && !(empty($_REQUEST['customer_name']))) {
    $customer_name = $_REQUEST['customer_name'];
} else {
    returnError("Nhập customer_name");
}


if (isset($_REQUEST['customer_password']) && !(empty($_REQUEST['customer_password']))) {
    if (is_password($_REQUEST['customer_password'])) {
        $customer_password = md5($_REQUEST['customer_password']);
    } else {
        returnError("customer_password không đúng định dạng");
    }
} else {
    returnError("Nhập customer_password");
}

if (isset($_REQUEST['customer_cert_no']) && !(empty($_REQUEST['customer_cert_no']))) {
    if (is_cert($_REQUEST['customer_cert_no'])) {
        $customer_cert_no = $_REQUEST['customer_cert_no'];
    } else {
        returnError("customer_cert_no không đúng định dạng");
    }
} else {
    returnError("Nhập customer_cert_no");
}

if (isset($_FILES['customer_cert_img'])) { // up product_img
    $customer_cert_img = 'customer_cert_img';
    $dir_save_customer_cert_img = "images/customer_customer/"; // sửa đường dẫn
} else {
    returnError("Nhập customer_cert_img");
}

$sql = "SELECT * FROM tbl_customer_customer WHERE customer_phone = '$customer_phone'";
$result = db_qr($sql);
if ((db_nums($result)) > 0) {
    returnError("Đã tồn tại tài khoản");
}
$dir_save_cert_img = handing_file_img($customer_cert_img, $dir_save_customer_cert_img);
$customer_code = "KH" . substr(time(), -8);
$customer_token = md5($username . time());
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
    returnSuccess("Đăng kí tài khoản thành công");
} else {
    returnError("Đăng kí không thành công");
}
