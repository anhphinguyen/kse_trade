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
    case 'list_customer_history': {
        include_once "./viewlist_board/list_customer_history.php";
    }
    default:
    returnError("type_manager is not accept!");
    break;
}
