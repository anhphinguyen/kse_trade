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
    case 'get_customer_factor': {
            include_once 'admin_board/get_customer_factor.php';
            break;
        }
    case 'config_percent_system': {
            include_once 'admin_board/config_percent_system.php';
            break;
        }
    case 'update_customer_factor': {
            include_once 'admin_board/update_customer_factor.php';
            break;
        }
    case 'get_percent_system': {
            include_once 'admin_board/get_percent_system.php';
            break;
        }
    case 'edit_customer_wallet': {
            include_once 'admin_board/edit_customer_wallet.php';
            break;
        }
    case 'notify_manager': {
            include_once 'admin_board/notify_manager.php';
            break;
        }
    case 'advertisement_manager': {
            include_once 'admin_board/advertisement_manager.php';
            break;
        }
    case 'exchange_update': {
            include_once 'admin_board/exchange_update.php';
            break;
        }
    case 'set_open_exchange': {
            include_once 'admin_board/set_open_exchange.php';
            break;
        }
    case 'service_exchange': {
            include_once 'admin_board/service_exchange.php';
            break;
        }
    case 'active_exchange': {
            include_once 'admin_board/active_exchange.php';
            break;
        }
    case 'account_manager': {
            include_once 'admin_board/account_manager.php';
            break;
        }
    case 'account_type_manager': {
            include_once 'admin_board/account_type_manager.php';
            break;
        }
    case 'app_deploy_manager': {
            include_once 'admin_board/app_deploy_manager.php';
            break;
        }
    case 'customer_manager': {
            include_once 'admin_board/customer_manager.php';
            break;
        }
    case 'deposit_manager': {
            include_once 'admin_board/deposit_manager.php';
            break;
        }
    case 'payment_manager': {
            include_once 'admin_board/payment_manager.php';
            break;
        }
    case 'exchange_manager': {
            include_once 'admin_board/exchange_manager.php';
            break;
        }
    case 'force_signout': {
            include_once 'admin_board/force_signout.php';
            break;
        }
        /*employee board*/
    case 'sale_support_customer_for_admin': {
            include_once 'employee_board/sale_support_customer_for_admin.php';
            break;
        }
    case 'sale_support_customer_list_customer': {
            include_once 'employee_board/sale_support_customer_list_customer.php';
            break;
        }
    case 'sale_update_info': {
            include_once 'employee_board/sale_update_info.php';
            break;
        }
    case 'sale_support_customer': {
            include_once 'employee_board/sale_support_customer.php';
            break;
        }
    case 'officer_support': {
            include_once 'employee_board/officer_support.php';
            break;
        }
    case 'officer_payment_request': {
            include_once 'employee_board/officer_payment_request.php';
            break;
        }
    case 'officer_edit_info': {
            include_once 'employee_board/officer_edit_info.php';
            break;
        }
        /*customer board*/
    case 'check_customer_authend': {
            include_once 'customer_board/check_customer_authend.php';
            break;
         }
    case 'add_money_win': {
            include_once 'customer_board/add_money_win.php';
            break;
         }
    case 'check_customer_disable': {
            include_once 'customer_board/check_customer_disable.php';
            break;
         }
    case 'check_customer_payment_reward': {
            include_once 'customer_board/check_customer_payment_reward.php';
            break;
         }

     case 'customer_deposit_reward': {
            include_once 'customer_board/customer_deposit_reward.php';
            break;
         }
         
    case 'forgot_password': {
            include_once 'customer_board/forgot_password.php';
            break;
        }
    case 'check_method_payment': {
            include_once 'customer_board/customer_check_method_payment.php';
            break;
        }
    case 'check_customer_balance': {
            include_once 'customer_board/check_customer_balance.php';
            break;
        }
    case 'check_isset_customer': {
            include_once 'customer_board/check_isset_customer.php';
            break;
        }
    case 'customer_request_support': {
            include_once 'customer_board/customer_request_support.php';
            break;
        }
    case 'check_exchange_open': {
            include_once 'customer_board/check_exchange_open.php';
            break;
        }
    case 'demo_trading': {
            include_once 'customer_board/demo_trading.php';
            break;
        }
    case 'demo_register': {
            include_once 'customer_board/demo_register.php';
            break;
        }

    case 'get_time_duration': {
            include_once 'customer_board/get_time_duration.php';
            break;
        }
    case 'get_coordinate': {
            include_once 'customer_board/get_coordinate.php';
            break;
        }
    case 'customer_trading': {
            include_once 'customer_board/customer_trading.php';
            break;
        }
    case 'customer_method_payment': {
            include_once 'customer_board/customer_method_payment.php';
            break;
        }
    case 'customer_trade_one_period': {
            include_once 'customer_board/customer_trade_one_period.php';
            break;
        }
    case 'customer_result_bet': {
            include_once 'customer_board/customer_result_bet.php';
            break;
        }
    case 'customer_request_payment': {
            include_once 'customer_board/customer_request_payment.php';
            break;
        }
    case 'customer_request_deposit': {
            include_once 'customer_board/customer_request_deposit.php';
            break;
        }
    case 'customer_check_service': {
            include_once 'customer_board/customer_check_service.php';
            break;
        }
    case 'register': {
            include_once 'customer_board/register.php';
            break;
        }
        /*socket board*/
    case 'reset_trading_log': {
            include_once 'socket_board/reset_trading_log.php';
            break;
        }
    case 'add_money_win_socket': {
            include_once 'socket_board/add_money_win_socket.php';
            break;
        }
    case 'get_detail_exchange': {
            include_once 'socket_board/get_detail_exchange.php';
            break;
        }

    case 'trading_block_result': {
            include_once 'socket_board/trading_block_result.php';
            break;
        }
    case 'auto_creat_session': {
            include_once 'socket_board/auto_creat_session_tomorrow.php';
            break;
        }
    case 'win_lose_trade': {
            include_once 'socket_board/win_lose_trade.php';
            break;
        }
    case 'check_time_block': {
            include_once 'socket_board/check_time_block.php';
            break;
        }
    case 'add_coordinate': {
            include_once 'socket_board/add_coordinate.php';
            break;
        }
        /*viewlist board*/
    case 'statictis_for_sales': {
            include_once 'viewlist_board/statictis_for_sales.php';
            break;
        }
    case 'list_history_bonus': {
            include_once 'viewlist_board/list_history_bonus.php';
            break;
        }
    case 'list_history_invest': {
            include_once 'viewlist_board/list_history_invest.php';
            break;
        }
    case 'check_customer_error': {
            include_once 'viewlist_board/check_customer_error.php';
            break;
        }
    case 'statictis_money': {
            include_once 'viewlist_board/statictis_money.php';
            break;
        }
    case 'list_customer_join_bet': {
            include_once 'viewlist_board/list_customer_join_bet.php';
            break;
        }
    case 'get_notify': {
            include_once 'viewlist_board/get_notify.php';
            break;
        }
    case 'check_sign_out': {
            include_once 'viewlist_board/check_sign_out.php';
            break;
        }
    case 'list_period_history': {
            include_once 'viewlist_board/list_period_history.php';
            break;
        }
    case 'list_bank': {
            include_once 'viewlist_board/list_bank.php';
            break;
        }
    case 'login': {
            include_once 'viewlist_board/login.php';
            break;
        }
    case 'change_pass': {
            include_once 'viewlist_board/change_pass.php';
            break;
        }
    case 'list_period_result': {
            include_once 'viewlist_board/list_period_result.php';
            break;
        }
    case 'list_customer_customer': {
            include_once 'viewlist_board/list_customer_customer.php';
            break;
        }
    case 'list_customer_detail': {
            include_once 'viewlist_board/list_customer_detail.php';
            break;
        }
    case 'list_customer_history': {
            include_once 'viewlist_board/list_customer_history.php';
            break;
        }
        case 'list_customer_history_reward': {
            include_once 'viewlist_board/list_customer_history_reward.php';
            break;
        }
    case 'list_deposit_detail': {
            include_once 'viewlist_board/list_deposit_detail.php';
            break;
        }
    case 'list_payment_detail': {
            include_once 'viewlist_board/list_payment_detail.php';
            break;
        }
    case 'list_request_deposit': {
            include_once 'viewlist_board/list_request_deposit.php';
            break;
        }
    case 'list_request_payment': {
            include_once 'viewlist_board/list_request_payment.php';
            break;
        }

    default: {
            echo json_encode(array(
                'success' => 'false',
                'massage' => 'detect has been failed'
            ));
        }
}
