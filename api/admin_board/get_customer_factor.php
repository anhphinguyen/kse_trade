<?php

$sql = "SELECT id, customer_fullname, customer_phone, customer_factor,customer_cert_no
        FROM tbl_customer_customer
        WHERE customer_virtual = 'N'
        AND customer_disable = 'N'
        AND customer_factor != '1'
        ";
$result_arr = array();
$result_arr['success'] = "true";
$result_arr['data'] = array();

$result = db_qr($sql);
if (db_nums($result) > 0) {
    while ($row = db_assoc($result)) {
        $result_item = array(
            'id_customer' => $row['id'],
            'customer_name' => $row['customer_fullname'],
            'customer_fullname' => $row['customer_fullname'],
            'customer_phone' => $row['customer_phone'],
            'customer_cert_no' => (!empty($row['customer_cert_no']))?$row['customer_cert_no']:"",
            'customer_factor' => $row['customer_factor'],
        );
        array_push($result_arr['data'], $result_item);
    }
}
reJson($result_arr);
