<?php

$header_arr = apache_request_headers();
if (isset($header_arr['Authorization']) && !empty($header_arr['Authorization'])) {
    $author = explode(" ", $header_arr['Authorization']);
    if (count($author) != 2) {
        errorToken("4003", "4003");
    }
    if ($author[0] != "Bearer") {
        errorToken("4003", "4003");
    }

    $token = $author[1];


    $sql = "SELECT * FROM `tbl_account_account` WHERE `account_token` = '$token'";
    // returnSuccess($sql);
    $result = db_qr($sql);
    $nums = db_nums($result);
    if ($nums == 0) {
        $sql = "SELECT * FROM `tbl_customer_customer` WHERE `customer_token` = '$token'";
        $result = db_qr($sql);
        $nums = db_nums($result);
        if ($nums == 0) {
            errorToken("4003", "4003");
        }
    }
} else {
    errorToken("4003", "4003");
}
