<?php
$sql = "SELECT * FROM tbl_popup_popup";


$result = db_qr($sql);
$nums = db_nums($result);
$result_arr = array();
$result_arr['success'] = 'true';
$result_arr['data'] = array();
if ($nums > 0) {
    while ($row = db_assoc($result)) {
        $result_item = array(
            'id_popup' => $row['id'],
            'id_account' => $row['id_account'],
            'popup_title' => $row['popup_title'],
            'popup_content' => $row['popup_content'],
        );
        array_push($result_arr['data'], $result_item);
    }
}
reJson($result_arr);