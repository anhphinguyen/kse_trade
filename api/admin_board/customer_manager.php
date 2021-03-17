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
    case 'resset_password_account': {
            $idUser = '';
            if (isset($_REQUEST['id_user'])) {
                if ($_REQUEST['id_user'] == '') {
                    unset($_REQUEST['id_user']);
                    returnError("Nhập id_user");
                }
            } else {
                returnError("Nhập id_user");
            }

            if (isset($_REQUEST['password_reset'])) {
                if ($_REQUEST['password_reset'] == '') {
                    unset($_REQUEST['password_reset']);
                    returnError("Nhập password_reset");
                }
            } else {
                returnError("Nhập password_reset");
            }

            $id_account = $_REQUEST['id_user'];
            $password_reset = $_REQUEST['password_reset'];

            $sql_check_account_exists = "SELECT * FROM tbl_customer_customer WHERE id = '" . $id_account . "'";

            $result_check = mysqli_query($conn, $sql_check_account_exists);
            $num_result_check = mysqli_num_rows($result_check);

            if ($num_result_check > 0) {

                $query = "UPDATE tbl_customer_customer SET ";
                $query .= "customer_password  = '" . md5(mysqli_real_escape_string($conn, $password_reset)) . "' ";
                $query .= "WHERE id = '" . $id_account . "'";
                // check execute query
                if ($conn->query($query)) {
                    returnSuccess("Cập nhật mật khẩu thành công!");
                } else {
                    returnError("Cập nhật mật khẩu không thành công!");
                }
            } else {
                returnError("Không tìm thấy tài khoản!");
            }
            exit();
            break;
        }
    case 'delete': {
            if (isset($_REQUEST['id_customer'])) {
                if ($_REQUEST['id_customer'] == '') {
                    unset($_REQUEST['id_customer']);
                    returnError("Nhập id_customer");
                } else {
                    $id_customer = $_REQUEST['id_customer'];
                }
            } else {
                returnError("Nhập id_customer");
            }

            $success = array();

            $sql = "SELECT `tbl_customer_customer`.`customer_cert_img` FROM `tbl_customer_customer` WHERE `id` = '{$id_customer}'";
            $result = mysqli_query($conn, $sql);
            $nums = mysqli_num_rows($result);
            if ($nums > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $customer_cert_img =  $row['customer_cert_img'];
                }
                if (file_exists("../" . $customer_cert_img)) {
                    @unlink("../" . $customer_cert_img);
                }
            }

            $sql = "SELECT `tbl_customer_customer`.`customer_account_img` FROM `tbl_customer_customer` WHERE `id` = '{$id_customer}'";
            $result = mysqli_query($conn, $sql);
            $nums = mysqli_num_rows($result);
            if ($nums > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $customer_account_img =  $row['customer_account_img'];
                }
                if (file_exists("../" . $customer_account_img)) {
                    @unlink("../" . $customer_account_img);
                }
            }

            $sql = "SELECT * FROM tbl_request_payment WHERE id_customer = '$id_customer'";
            $result = mysqli_query($conn, $sql);
            $nums = mysqli_num_rows($result);
            if ($nums > 0) {
                returnError("Không thể xóa khách hàng này");
            }
            $sql = "SELECT * FROM tbl_request_deposit WHERE id_customer = '$id_customer'";
            $result = mysqli_query($conn, $sql);
            $nums = mysqli_num_rows($result);
            if ($nums > 0) {
                returnError("Không thể xóa khách hàng này");
            }

            $sql = "SELECT * FROM tbl_trading_log WHERE id_customer = '$id_customer'";
            $result = mysqli_query($conn, $sql);
            $nums = mysqli_num_rows($result);
            if ($nums > 0) {
                returnError("Không thể xóa khách hàng này");
            }

            $sql = "SELECT * FROM tbl_support_customer WHERE id_customer = '$id_customer'";
            $result = mysqli_query($conn, $sql);
            $nums = mysqli_num_rows($result);
            if ($nums > 0) {
                returnError("Không thể xóa khách hàng này");
            }

            $sql = "DELETE FROM `tbl_customer_customer` WHERE `id` = '{$id_customer}'";
            if (db_qr($sql)) {
                $success['delete_customer'] = "true";
            }

            if (!empty($success)) {
                returnSuccess("Xóa thành công");
            } else {
                returnError("Xóa thất bại");
            }
            break;

            break;
        }
    case 'update': {

            if (isset($_REQUEST['id_customer'])) {
                if ($_REQUEST['id_customer'] == '') {
                    unset($_REQUEST['id_customer']);
                    returnError("Nhập id_customer");
                } else {
                    $id_customer = $_REQUEST['id_customer'];
                }
            } else {
                returnError("Nhập id_customer");
            }

            $success = array();
            if (isset($_REQUEST['customer_name']) && !empty($_REQUEST['customer_name'])) { //*
                $customer_name = htmlspecialchars($_REQUEST['customer_name']);
                $sql = "UPDATE `tbl_customer_customer` SET";
                $sql .= " `customer_fullname` = '{$customer_name}'";
                $sql .= " WHERE `id` = '{$id_customer}'";

                if (mysqli_query($conn, $sql)) {
                    $success['customer_name'] = "true";
                }
            }

            if (isset($_REQUEST['customer_introduce']) && !empty($_REQUEST['customer_introduce'])) {
                $customer_introduce = htmlspecialchars($_REQUEST['customer_introduce']);
                $sql = "UPDATE `tbl_customer_customer` SET";
                $sql .= " `customer_introduce` = '{$customer_introduce}'";
                $sql .= " WHERE `id` = '{$id_customer}'";

                if (mysqli_query($conn, $sql)) {
                    $success['customer_introduce'] = "true";
                }
            }

            if (isset($_REQUEST['customer_limit_payment']) && !empty($_REQUEST['customer_limit_payment'])) {
                $customer_limit_payment = htmlspecialchars($_REQUEST['customer_limit_payment']);
                $sql = "UPDATE `tbl_customer_customer` SET";
                $sql .= " `customer_limit_payment` = '{$customer_limit_payment}'";
                $sql .= " WHERE `id` = '{$id_customer}'";

                if (mysqli_query($conn, $sql)) {
                    $success['customer_limit_payment'] = "true";
                }
            }

            if (isset($_FILES['customer_cert_img'])) {
                $sql = "SELECT * FROM `tbl_customer_customer` WHERE `id` = '{$id_customer}'";
                $result = mysqli_query($conn, $sql);
                $nums = mysqli_num_rows($result);
                if ($nums > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $customer_cert_img = $row['customer_cert_img'];
                        if (file_exists("../" . $customer_cert_img)) {
                            @unlink("../" . $customer_cert_img);
                        }
                    }
                }
                $customer_cert_img = 'customer_cert_img';
                $dir_save_customer_cert_img = "images/customer_customer/";
                $dir_save_cert_img = handing_file_img($customer_cert_img, $dir_save_customer_cert_img);
                $sql = "UPDATE `tbl_customer_customer`
                    SET `customer_cert_img` = '{$dir_save_cert_img}' 
                    WHERE `id` = '{$id_customer}'";
                if (mysqli_query($conn, $sql)) {
                    $success['customer_cert_img'] = 'true';
                }
            }

            if (isset($_REQUEST['id_bank']) && !empty($_REQUEST['id_bank'])) {
                $id_bank = $_REQUEST['id_bank'];
                $sql = "UPDATE `tbl_customer_customer` SET";
                $sql .= " `id_bank` = '{$id_bank}'";
                $sql .= " WHERE `id` = '{$id_customer}'";

                if (mysqli_query($conn, $sql)) {
                    $success['id_bank'] = "true";
                }
            }

            if (isset($_REQUEST['customer_account_no']) && !empty($_REQUEST['customer_account_no'])) {
                $customer_account_no = $_REQUEST['customer_account_no'];
                $sql = "UPDATE `tbl_customer_customer` SET";
                $sql .= " `customer_account_no` = '{$customer_account_no}'";
                $sql .= " WHERE `id` = '{$id_customer}'";

                if (mysqli_query($conn, $sql)) {
                    $success['customer_account_no'] = "true";
                }
            }
            if (isset($_REQUEST['customer_account_holder']) && !empty($_REQUEST['customer_account_holder'])) {
                $customer_account_holder = $_REQUEST['customer_account_holder'];
                $sql = "UPDATE `tbl_customer_customer` SET";
                $sql .= " `customer_account_holder` = '{$customer_account_holder}'";
                $sql .= " WHERE `id` = '{$id_customer}'";

                if (mysqli_query($conn, $sql)) {
                    $success['customer_account_holder'] = "true";
                }
            }


            if (isset($_FILES['customer_account_img'])) {
                $sql = "SELECT * FROM `tbl_customer_customer` WHERE `id` = '{$id_customer}'";
                $result = mysqli_query($conn, $sql);
                $nums = mysqli_num_rows($result);
                if ($nums > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $customer_account_img = $row['customer_account_img'];
                        if (file_exists("../" . $customer_account_img)) {
                            @unlink("../" . $customer_account_img);
                        }
                    }
                }
                $customer_account_img = 'customer_account_img';
                $dir_save_customer_account_img = "images/customer_customer/";
                $dir_save_account_img = handing_file_img($customer_account_img, $dir_save_customer_account_img);
                $sql = "UPDATE `tbl_customer_customer`
                    SET `customer_account_img` = '{$dir_save_account_img}' 
                    WHERE `id` = '{$id_customer}'";
                if (mysqli_query($conn, $sql)) {
                    $success['customer_account_img'] = 'true';
                }
            }
            if (!empty($success)) {
                returnSuccess("Cập nhật thành công");
            } else {
                returnError("Không có thông tin cập nhật");
            }
            break;
        }
    case 'create': {

            if (isset($_REQUEST['customer_name'])) {   //*
                if ($_REQUEST['customer_name'] == '') {
                    unset($_REQUEST['customer_name']);
                    returnError("Nhập tên khách hàng");
                } else {
                    $customer_name = htmlspecialchars($_REQUEST['customer_name']);
                }
            } else {
                returnError("Nhập tên khách hàng");
            }


            if (isset($_REQUEST['customer_phone'])) {  //*
                if ($_REQUEST['customer_phone'] == '') {
                    unset($_REQUEST['customer_phone']);
                    returnError("Nhập số điện thoại");
                } else {
                    $customer_phone = htmlspecialchars($_REQUEST['customer_phone']);
                }
            } else {
                returnError("Nhập số điện thoại");
            }

            if (isset($_REQUEST['customer_password'])) {  //*
                if ($_REQUEST['customer_password'] == '') {
                    unset($_REQUEST['customer_password']);
                    returnError("Nhập số mật khẩu");
                } else {
                    $customer_password = md5($_REQUEST['customer_password']);
                }
            } else {
                returnError("Nhập số mật khẩu");
            }

            if (isset($_REQUEST['customer_introduce'])) {
                if ($_REQUEST['customer_introduce'] == '') {
                    unset($_REQUEST['customer_introduce']);
                } else {
                    $customer_introduce = htmlspecialchars($_REQUEST['customer_introduce']);
                }
            }
            if (isset($_REQUEST['customer_cert_no'])) {
                if ($_REQUEST['customer_cert_no'] == '') {
                    unset($_REQUEST['customer_cert_no']);
                } else {
                    $customer_cert_no = htmlspecialchars($_REQUEST['customer_cert_no']);
                }
            }else {
                returnError("Nhập số CMND");
            }

            if (isset($_REQUEST['customer_limit_payment'])) {
                if ($_REQUEST['customer_limit_payment'] == '') {
                    unset($_REQUEST['customer_limit_payment']);
                } else {
                    $customer_limit_payment = htmlspecialchars($_REQUEST['customer_limit_payment']);
                }
            }

            if (isset($_FILES['customer_cert_img'])) { // up product_img
                $customer_cert_img = 'customer_cert_img';
                $dir_save_customer_cert_img = "images/customer_customer/"; // sửa đường dẫn
            } else {
                returnError("Chụp ảnh CMND mặt trước");
            }

            if (isset($_REQUEST['id_bank'])) {
                if ($_REQUEST['id_bank'] == '') {
                    unset($_REQUEST['id_bank']);
                } else {
                    $id_bank = $_REQUEST['id_bank'];
                }
            }
            if (isset($_REQUEST['customer_account_no'])) {
                if ($_REQUEST['customer_account_no'] == '') {
                    unset($_REQUEST['customer_account_no']);
                } else {
                    $customer_account_no = $_REQUEST['customer_account_no'];
                }
            }
            if (isset($_REQUEST['customer_account_holder'])) {
                if ($_REQUEST['customer_account_holder'] == '') {
                    unset($_REQUEST['customer_account_holder']);
                } else {
                    $customer_account_holder = $_REQUEST['customer_account_holder'];
                }
            }

            if (isset($_FILES['customer_account_img'])) { // up product_img
                $customer_account_img = 'customer_account_img';
                $dir_save_customer_account_img = "images/customer_customer/"; // sửa đường dẫn
                $dir_save_account_img = handing_file_img($customer_account_img, $dir_save_customer_account_img);
            }
            // else {
            //     returnError("Nhập customer_account_img");
            // }


            $sql = "SELECT * FROM `tbl_customer_customer` 
                            WHERE `customer_phone` = '{$customer_phone}'
                            ";
            $result = db_qr($sql);
            $nums = db_nums($result);
            if ($nums > 0) {
                returnError("Đã tồn tại khách hàng này");
            }
            // Tạo mã khách hàng
            $customer_code = "KH" . substr(time(), -8);

            $dir_save_cert_img = handing_file_img($customer_cert_img, $dir_save_customer_cert_img);
            
            $sql = "INSERT INTO `tbl_customer_customer` SET 
                                                `customer_fullname` = '{$customer_name}',
                                                `customer_code` = '{$customer_code}',
                                                `customer_phone` = '{$customer_phone}',
												`customer_password` = '{$customer_password}'
                                                ";
            if (isset($customer_introduce) && !empty($customer_introduce)) {
                $sql .= " ,`customer_introduce` = '{$customer_introduce}'";
            }
            if (isset($customer_limit_payment) && !empty($customer_limit_payment)) {
                $sql .= " ,`customer_limit_payment` = '{$customer_limit_payment}'";
            }
            if (isset($customer_cert_no) && !empty($customer_cert_no)) {
                $sql .= " ,`customer_cert_no` = '{$customer_cert_no}'";
            }
            if (isset($dir_save_cert_img) && !empty($dir_save_cert_img)) {
                $sql .= " ,`customer_cert_img` = '{$dir_save_cert_img}'";
            }
            if (isset($id_bank) && !empty($id_bank)) {
                $sql .= " ,`id_bank` = '{$id_bank}'";
            }
            if (isset($customer_account_no) && !empty($customer_account_no)) {
                $sql .= " ,`customer_account_no` = '{$customer_account_no}'";
            }
            if (isset($customer_account_holder) && !empty($customer_account_holder)) {
                $sql .= " ,`customer_account_holder` = '{$customer_account_holder}'";
            }
            if (isset($dir_save_account_img) && !empty($dir_save_account_img)) {
                $sql .= " ,`customer_account_img` = '{$dir_save_account_img}'";
            }


            if (mysqli_query($conn, $sql)) {
                returnSuccess("Tạo khách hàng thành công");
            } else {
                returnError("Tạo khách hàng không thành công");
            }
            break;
        }
    case 'list_customer_history': {
            include_once "./viewlist_board/list_customer_history.php";
            break;
        }

    case 'list_customer_detail': {
            include_once "./viewlist_board/list_customer_detail.php";
            break;
        }


    case 'list_customer_customer': {
            include_once "./viewlist_board/list_customer_customer.php";
            break;
        }
    default: {
            returnError("Khong ton tai type_manager");
            break;
        }
}
