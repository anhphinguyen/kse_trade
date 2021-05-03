<?php
if (isset($_REQUEST['type_account'])) {
    if ($_REQUEST['type_account'] == '') {
        unset($_REQUEST['type_account']);
        returnError("Nhập type_account");
    }
}

if (!isset($_REQUEST['type_account'])) {
    returnError("Nhập type_account");
}

$type_account = $_REQUEST['type_account'];

if (isset($_REQUEST['id_account'])) {
    if ($_REQUEST['id_account'] == '') {
        unset($_REQUEST['id_account']);
        returnError("Nhập id_account");
    }
}

if (!isset($_REQUEST['id_account'])) {
    returnError("Nhập id_account");
}

$id_account = $_REQUEST['id_account'];



switch ($type_account) {
    case 'customer':
        $sql = "SELECT * FROM `tbl_customer_customer` WHERE `id` = '{$id_account}' ";
        $result = mysqli_query($conn, $sql);
        $nums = mysqli_num_rows($result);
        if ($nums > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $password = $row['customer_password'];
            }
        }
        break;
    case 'employee':
        $sql = "SELECT * FROM `tbl_account_account` WHERE `id` = '{$id_account}' ";
        $result = mysqli_query($conn, $sql);
        $nums = mysqli_num_rows($result);
        if ($nums > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $password = $row['account_password'];
            }
        }
        break;
}


if (isset($_REQUEST['old_pass']) && !empty($_REQUEST['old_pass'])) {
    if (!is_password($_REQUEST['old_pass'])) {
        returnError("Mật khẩu không đúng định dạng");
    } elseif (md5($_REQUEST['old_pass']) != $password) {
        returnError("Mật khẩu cũ không trùng khớp");
    } else {
        $old_pass = md5($_REQUEST['old_pass']);
    }
}else{
    returnError("Nhập old_pass");
}

if (isset($_REQUEST['new_pass'])) {
    if (!is_password($_REQUEST['new_pass'])) {
        returnError("Mật khẩu không đúng định dạng");
    } elseif ($_REQUEST['new_pass'] == $_REQUEST['old_pass']) {
        returnError("Mật khẩu mới phải khác mật khẩu cũ");
    } else {
        $new_pass = md5($_REQUEST['new_pass']);
    }
}else{
    returnError("Nhập new_pass");
}

switch ($type_account) {
    case 'customer':
        $sql = "UPDATE `tbl_customer_customer` 
                SET `customer_password` = '{$new_pass}' 
                WHERE `id` = '{$id_account}'
                ";
        break;
    case 'employee':
        $sql = "UPDATE `tbl_account_account` 
                SET `account_password` = '{$new_pass}' 
                WHERE `id` = '{$id_account}'
                ";
        break;
}

if (db_qr($sql)) {
    returnSuccess("Đổi mật khẩu thành công");
} else {
    returnError("Đổi mật khẩu không thành công.");
}
