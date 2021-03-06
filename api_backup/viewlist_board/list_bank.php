<?php


$sql = "SELECT * FROM tbl_bank_info";

if (isset($_REQUEST['filter'])) {
    if ($_REQUEST['filter'] == '') {
        unset($_REQUEST['filter']);
    } else {
        $filter = htmlspecialchars($_REQUEST['filter']);
        $sql .= " AND ( `bank_full_name` LIKE '%{$filter}%'";
        $sql .= " OR `bank_short_name` LIKE '%{$filter}%')";
    }
}


$result = db_qr($sql);
$nums = db_nums($result);
$result_arr = array();
if ($nums > 0) {
    $result_arr['success'] = 'true';
    $result_arr['data'] = array();
    while ($row = db_assoc($result)) {
        $result_item = array(
            'id_bank' => $row['id'],
            'bank_name' => $row['bank_full_name'],
            'bank_short_name' => $row['bank_short_name'],
            'bank_code' => $row['bank_code'],
        );
        array_push($result_arr['data'], $result_item);
    }
    reJson($result_arr);
}
