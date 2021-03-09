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

switch ($typeManager) {
    case 'list_customer':{
        // $sql = "SELECT 
        //             tbl_customer_customer.customer_fullname,
        //             tbl_customer_customer.customer_phone,
        //             tbl_customer_customer.customer_,
        //         "
        // break;
    }
    case 'list_customer_history': {
        include_once "./viewlist_board/list_customer_history.php";
        break;
    }
    default:
    returnError("type_manager is not accept!");
    break;
}
