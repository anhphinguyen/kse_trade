<?php
if(isset($_REQUEST['id_period']) && !empty($_REQUEST['id_period'])){
    $id_period = $_REQUEST['id_period'];
}else{
    returnError("Nhập id_period");
}

$sql = "SELECT id_period,result_type,result_flag FROM tbl_result_temporary WHERE id_period = '$id_period'";

$result = db_qr($sql);
$nums = db_nums($result);
if ($nums > 0) {
    while ($row = db_assoc($result)) {
        if($row['result_flag'] === 'F'){
            
            $sql_update_flag = "UPDATE tbl_result_temporary SET result_flag = 'T' WHERE id_period = '$id_period'";
            db_qr($sql_update_flag);

            if($row['result_type'] === 'up'){
                result_up($row['id_period']);
            }elseif($row['result_type'] === 'down'){
                result_down($row['id_period']);
            }else{
                returnError('Lỗi result');
            } 
            returnSuccess("true");
        }
    }
}
returnSuccess("error");