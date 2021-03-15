<?php

$id_account = '';
if (isset($_REQUEST['id_account']) && ! empty($_REQUEST['id_account'])) {
    $id_account = $_REQUEST['id_account'];
}else{
    returnError("Nhập id_account!");
}


$sql = "SELECT * FROM `tbl_account_account` WHERE `id` = {$id_account}";


$result = mysqli_query($conn, $sql);

$num_row = mysqli_num_rows($result);

$result_arr = array();
$result_arr['success'] = 'true';

if ($num_row > 0) {
    while ($row = $result->fetch_assoc()) {
        $user_item = array(
            'id' => $row['id'],
            'force_sign_out' =>  $row['force_sign_out']
        );
        
        $result_arr['data'] = array(
            $user_item
        );
    }
}else{
    $result_arr['message'] = 'Không tìm thấy user!';
}

echo json_encode($result_arr);