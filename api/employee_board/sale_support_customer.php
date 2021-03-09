<?php 
if (isset($_REQUEST['type_manager'])) {
    if ($_REQUEST['type_manager'] == '') {
        unset($_REQUEST['type_manager']);
        returnError("Nhập type_manager");
    } else {
        $type_manager = $_REQUEST['type_manager'];
    }
} else {
    returnError("Nhập type_manager");
}

switch ($type_manager) {
    case 'list_customer':{
        $sql = "SELECT 
                tbl_customer_customer.customer_fullname,
                tbl_customer_customer.customer_phone,
                tbl_trading_log.id_customer,
                tbl_trading_log.trading_log
                FROM tbl_customer_customer
                LEFT JOIN tbl_trading_log ON tbl_customer_customer.id = tbl_trading_log.id_customer
                WHERE 1=1 
                "; 
        $result = db_qr($sql);
        $nums = db_nums($result);
        if($nums > 0){
            
        }
            echo $sql;
            exit();
        break;
    }
    case 'list_customer_history': {
        include_once "./viewlist_board/list_customer_history.php";
        break;
    }
    default:
    returnError("type_manager is not accept!");
    break;
}
