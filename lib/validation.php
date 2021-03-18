<?php
//@Hàm : is_username
//@Tham số: $username cần kiểm tra
//@Trả về: True nếu đúng định dạng username
function is_username($username)
{
    $parttern = "/^[A-Za-z0-9_\.]{1,32}$/";
    if (preg_match($parttern, $username))
        return true;
}


function is_cert($cert_no)
{
    // $parttern = "/^([A-Z]){1}([\w_\.!@#$%^&*()]+){5,31}$/";
    $parttern = "/^([0-9]+){9,12}$/";
    if (preg_match($parttern, $cert_no))
        return true;
}
//@Hàm : is_password
//@Tham số: chuỗi password cần kiểm tra
//@Trả về: True nếu đúng định dạng password
function is_password($password)
{
    // $parttern = "/^([A-Z]){1}([\w_\.!@#$%^&*()]+){5,31}$/";
    $parttern = "/^([\w_\.!@#$%^&*()]+){6,31}$/";
    if (preg_match($parttern, $password))
        return true;
}
//@Hàm : is_email
//@Tham số: chuỗi Email cần kiểm tra
//@Trả về: True nếu đúng định dạng Email
function is_email($email)
{
    $parttern = "/^[A-Za-z0-9_.]{6,32}@([a-zA-Z0-9]{2,12})(.[a-zA-Z]{2,12})+$/";
    if (preg_match($parttern, $email))
        return true;
}

//@Hàm : set_value
function set_value($label_field)
{
    global $$label_field;
    if (isset($$label_field))
        echo $$label_field;
}

//@Hàm : form_error
function form_error($label_field)
{
    global $error;
    if (isset($error[$label_field])) {
        echo "<span style=\"color:#f64d4d;\">{$error[$label_field]}</span><br/>";
    }
}
