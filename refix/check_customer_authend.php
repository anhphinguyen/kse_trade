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


$sql = "SELECT  id,
                customer_authend 
                FROM tbl_customer_customer
                WHERE tbl_customer_customer.id = '{$id_customer}'";

$customer_arr = array();
$customer_arr['success'] = 'true';

$customer_arr['data'] = array();
$result = db_qr($sql);
$nums = db_nums($result);

if(db_nums($result) > 0){
    while($row = db_assoc($result)){
        $customer_item = array(
            'id_customer' => $row['id'],
            'customer_authend' => $row['customer_authend']
        );
        array_push($customer_arr['data'], $customer_item);
    }
    reJson($customer_arr);
}else{
    returnError("Lỗi truy vấn");
}
