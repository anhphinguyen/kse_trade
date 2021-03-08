<?php 

if (isset($_REQUEST['id_demo'])) {
    if ($_REQUEST['id_demo'] == '') {
        unset($_REQUEST['id_demo']);
        returnError("Nhập id_demo");
    } else {
        $demo_name = $_REQUEST['id_demo'];
    }
} else {
    returnError("Nhập id_demo");
}

$sql = "SELECT demo_wallet_payment FROM tbl_customer_demo WHERE id = '$id_demo'";
$result = db_qr($sql);
$nums = db_nums($result);
if($nums > 0){
    while($row = db_assoc($result)){
        $demo_wallet_payment = $row['demo_wallet_payment']; // value default is 100.000
    }
}

$sql = "UPDATE tbl_customer_demo SET
        demo_wallet_bet = '$demo_wallet_payment'
        WHERE id = '$id_demo'
        ";
if(db_qr($sql)){
    returnSuccess("Nạp tiền thành công");
}
