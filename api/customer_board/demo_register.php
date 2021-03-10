<?php
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

$str = "ABCDEFGHIJKLMNOPQRTUVXYZWabcdefghijklmnopqrtuvxyzw1234567890";
$demo_token = md5($demo_name.time());

$sql = "INSERT INTO tbl_customer_demo SET
        demo_name = '$demo_name',
        demo_token = '$demo_token',
        force_sign_out = '0'
        ";
if(db_qr($sql)){
    $id_demo = mysqli_insert_id($conn);
    $sql = "SELECT * FROM tbl_customer_demo WHERE id = '$id_demo'";

    $result_arr = array();
    $result_arrp['success'] = "true";
    $result_arr['data'] = array();
    
    $result = db_qr($sql);
    $nums = db_nums($result);
    if($nums > 0){
        while($row = db_assoc($result)){
            $result_item = array(
                'id_demo' => $row['id_demo'],
                'demo_name' => $row['demo_name'],
                'demo_wallet_bet' => $row['demo_wallet_bet'],
                'demo_token' => $row['demo_token'],
            );
        }
        array_push($result_arr['data'],$result_item);
    }
    reJson($result_arr);
}else{
    returnError("Đăng kí tài khoản demo thất bại");
}
