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

if (isset($_REQUEST['id_bank'])) {
    if ($_REQUEST['id_bank'] == '') {
        unset($_REQUEST['id_bank']);
        returnError("Nhập id_bank");
    } else {
        $id_bank = $_REQUEST['id_bank'];
    }
} else {
    returnError("Nhập id_bank");
}

if (isset($_REQUEST['customer_account_no'])) {
    if ($_REQUEST['customer_account_no'] == '') {
        unset($_REQUEST['customer_account_no']);
        returnError("Nhập customer_account_no");
    } else {
        $customer_account_no = $_REQUEST['customer_account_no'];
    }
} else {
    returnError("Nhập customer_account_no");
}

if (isset($_REQUEST['customer_account_holder'])) {
    if ($_REQUEST['customer_account_holder'] == '') {
        unset($_REQUEST['customer_account_holder']);
        returnError("Nhập customer_account_holder");
    } else {
        $customer_account_holder = $_REQUEST['customer_account_holder'];
    }
} else {
    returnError("Nhập customer_account_holder");
}

if (isset($_FILES['customer_account_img'])) { // up product_img
    $customer_account_img = 'customer_account_img';
    $dir_save_customer_account_img = "images/customer_customer/"; // sửa đường dẫn
} else {
    returnError("Nhập customer_account_img");
}


$dir_save_account_img = handing_file_img($customer_account_img, $dir_save_customer_account_img);
$sql = "UPDATE `tbl_customer_customer` SET 
        `id_bank` = '{$id_bank}',
        `customer_account_no` = '{$customer_account_no}',
        `customer_account_holder` = '{$customer_account_holder}',
        `customer_account_img` = '{$dir_save_account_img}'
        WHERE id = '$id_customer'
        ";

if (db_qr($sql)) {
    returnSuccess("Cập nhật phương thức thanh toán thành công");
} else {
    returnError("Lỗi truy vấn");
}
