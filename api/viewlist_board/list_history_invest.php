<?php

if (isset($_REQUEST['id_customer']) && !empty($_REQUEST['id_customer'])) {
    $id_customer = $_REQUEST['id_customer'];
} else {
    returnError("Nhập id_customer");
}


$sql_total_money = "SELECT SUM(request_value) as total_money FROM tbl_request_bonus 
                    WHERE id_customer = '$id_customer'
                    AND request_type = '2'
                    ";
$result_total_money = db_qr($sql_total_money);
if(db_nums($result_total_money) > 0){
    while($row_total_money = db_assoc($result_total_money)){
        $total_money = $row_total_money['total_money'];
    }
}


$sql = "SELECT request_value,request_time_completed,request_code FROM tbl_request_bonus
        WHERE id_customer = '$id_customer' AND request_type = '2'
        ORDER BY `tbl_request_bonus`.`request_time_completed` DESC";

$result_arr = array();
$result_arr['success'] = "true";
$result_arr['total_money'] = (!empty($total_money))?strval($total_money):"";
$result_arr['data'] = array();
$result = db_qr($sql);
$nums = db_nums($result);

if ($nums > 0) {
    while ($row = db_assoc($result)) {

        $history_item = array(
            'request_value' => $row['request_value'],
            'request_time_completed' => date("d/m/Y - H:i", $row['request_time_completed']),
            'request_code' => $row['request_code']
        );

        array_push($result_arr['data'], $history_item);
    }
} 
reJson($result_arr);
