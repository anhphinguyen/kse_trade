<?php

$sql = "SELECT * FROM banks";
$result = db_qr($sql);
$nums = db_nums($result);
$result_arr = array();
if($nums > 0){
    while($row = db_assoc($result)){
        $result_item = array(
            'bank_name' => $row['vn_name'],
            'bank_code' => $row['bankCode'],
            'bank_short_name' => $row['shortName'],
        );
        array_push($result_arr, $result_item);
    }
    $bank_code = '';
    $bank_name = '';
    $bank_short_name = '';
    foreach($result_arr as $item){
        $bank_code = $item['bank_code'];
        $bank_name = $item['bank_name'];
        $bank_short_name = $item['bank_short_name'];
        $sql = "INSERT INTO tbl_bank_info SET
                bank_name = '$bank_name',
                bank_code = '$bank_code',
                bank_short_name = '$bank_short_name'
                ";
        db_qr($sql);
    }
}