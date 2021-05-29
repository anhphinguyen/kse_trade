<?php

$sql = "SELECT id, percent_status, percent_system FROM tbl_percent_system";

$result_arr = array();
$result_arr['success'] = "true";
$result_arr['data'] = array();

$result = db_qr($sql);
$nums = db_nums($result);



if($nums > 0){
    while($row = db_assoc($result)){
        $result_item = array(
            'id_percent' => $row['id'],
            'percent_status' => $row['percent_status'],
            'percent_system' => $row['percent_system'],
        );
        array_push($result_arr['data'], $result_item);
    }
}
reJson($result_arr);