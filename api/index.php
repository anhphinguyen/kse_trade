<?php
// include_once 'basic_auth.php';
include_once "../lib/database.php";
include_once "../lib/connect.php";
include_once "../lib/reuse_function.php";
include_once "../lib/validation.php";

// include_once "../vendor/autoload.php";

// include_once "../lib/jwt/php-jwt-master/src/JWT.php";

// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header("Access-Control-Allow-Methods: GET");
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

// check if data recived is from raw - if so, assign it to $_REQUEST
if (!isset($_REQUEST['detect'])) {
    // get raw json data
    $_REQUEST = json_decode(file_get_contents('php://input'), true);
    if (!isset($_REQUEST['detect'])) {
        echo json_encode(array(
            'message' => 'detect parameter not found !'
        ));
        exit();
    }
}
// handle detect value
$detect = $_REQUEST['detect'];

switch ($detect) {

    
    /*admin board*/
    case 'account_manager':{
        include_once 'admin_board/account_manager.php';
        break;
    }
    case 'account_type_manager':{
        include_once 'admin_board/account_type_manager.php';
        break;
    }
    case 'app_deploy_manager':{
        include_once 'admin_board/app_deploy_manager.php';
        break;
    }
    case 'customer_manager':{
        include_once 'admin_board/customer_manager.php';
        break;
    }
    case 'deposit_manager':{
        include_once 'admin_board/deposit_manager.php';
        break;
    }
    case 'payment_manager':{
        include_once 'admin_board/payment_manager.php';
        break;
    }
    case 'exchange_manager':{
        include_once 'admin_board/exchange_manager.php';
        break;
    }
    case 'force_signout':{
        include_once 'admin_board/force_signout.php';
        break;
    }
    /*customer board*/
    case 'register':{
        include_once 'customer_board/register.php';
        break;
    }
    /*viewlist board*/
    case 'login':{
        include_once 'viewlist_board/login.php';
        break;
    }
    case 'change_pass':{
        include_once 'socket_board/change_pass.php';
        break;
    }
    case 'list_customer_customer':{
        include_once 'socket_board/list_customer_customer.php';
        break;
    }
    case 'list_customer_detail':{
        include_once 'socket_board/list_customer_detail.php';
        break;
    }
    case 'list_customer_history':{
        include_once 'socket_board/list_customer_history.php';
        break;
    }
    case 'list_deposit_detail':{
        include_once 'socket_board/list_deposit_detail.php';
        break;
    }
    case 'list_payment_detail':{
        include_once 'socket_board/list_payment_detail.php';
        break;
    }
    case 'list_request_deposit':{
        include_once 'socket_board/list_request_deposit.php';
        break;
    }
    case 'list_request_payment':{
        include_once 'socket_board/list_request_payment.php';
        break;
    }

    default: {
            echo json_encode(array(
                'success' => 'false',
                'massage' => 'detect has been failed'
            ));
        }
}
