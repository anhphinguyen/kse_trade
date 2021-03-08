<?php 
$sql = "SELECT 
            *
            FROM `tbl_customer_demo`
            WHERE 1=1";

if (isset($_REQUEST['id_demo'])) {
    if ($_REQUEST['id_demo'] == '') {
        unset($_REQUEST['id_demo']);
        returnError("Nhập id_demo");
    } else {
        $id_demo = $_REQUEST['id_demo'];
        $sql .= " AND `tbl_customer_demo`.`id` = '{$id_demo}'";
    }
}

$customer_arr['success'] = 'true';
$customer_arr['data'] = array();

if ($nums > 0) {
    while ($row = db_assoc($result)) {
        $customer_item = array(
            'id_demo' => $row['id'],
            'demo_active' =>$row['demo_active'],
        );

        array_push($customer_arr['data'], $customer_item);
    }
    reJson($customer_arr);
} else {
    returnSuccess("Không tồn tại khách hàng này");
}
