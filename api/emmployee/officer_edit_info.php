<?php

if (isset($_REQUEST['id_account'])) {
    if ($_REQUEST['id_account'] == '') {
        unset($_REQUEST['id_account']);
        returnError("Nhập id_account");
    } else {
        $id_account = $_REQUEST['id_account'];
    }
} else {
    returnError("Nhập id_account");
}

$success = array();
if (isset($_REQUEST['account_name']) && !empty($_REQUEST['account_name'])) { //*
    $account_name = htmlspecialchars($_REQUEST['account_name']);
    $sql = "UPDATE `tbl_account_account` SET";
    $sql .= " `account_fullname` = '{$account_name}'";
    $sql .= " WHERE `id` = '{$id_account}'";

    if (mysqli_query($conn, $sql)) {
        $success['account_name'] = "true";
    }
}

if (isset($_REQUEST['account_email']) && !empty($_REQUEST['account_email'])) { //*
    $account_email = htmlspecialchars($_REQUEST['account_email']);
    $sql = "UPDATE `tbl_account_account` SET";
    $sql .= " `account_email` = '{$account_email}'";
    $sql .= " WHERE `id` = '{$id_account}'";

    if (mysqli_query($conn, $sql)) {
        $success['account_email'] = "true";
    }
}

if (isset($_REQUEST['account_phone']) && !empty($_REQUEST['account_phone'])) { //*
    $account_phone = htmlspecialchars($_REQUEST['account_phone']);
    $sql = "UPDATE `tbl_account_account` SET";
    $sql .= " `account_phone` = '{$account_phone}'";
    $sql .= " WHERE `id` = '{$id_account}'";

    if (mysqli_query($conn, $sql)) {
        $success['account_phone'] = "true";
    }
}


if (!empty($success)) {
    returnSuccess("Cập nhật thành công");
} else {
    returnSuccess("Không có thông tin cập nhật");
}