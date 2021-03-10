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
                $dir_save_account_img = handing_file_img($customer_account_img, $customer_account_img);
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
                    returnError("Nhap customer_name");
                } else {
                    $customer_name = htmlspecialchars($_REQUEST['customer_name']);
                }
            } else {
                returnError("Nhap customer_name");
            }


            if (isset($_REQUEST['customer_phone'])) {  //*
                if ($_REQUEST['customer_phone'] == '') {
                    unset($_REQUEST['customer_phone']);
                    returnError("Nhap customer_phone");
                } else {
                    $customer_phone = htmlspecialchars($_REQUEST['customer_phone']);
                }
            } else {
                returnError("Nhap customer_phone");
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
            }

            if (isset($_FILES['customer_cert_img'])) { // up product_img
                $customer_cert_img = 'customer_cert_img';
                $dir_save_customer_cert_img = "images/customer_customer/"; // sửa đường dẫn
            } else {
                returnError("Nhập customer_cert_img");
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
            } else {
                returnError("Nhập customer_account_img");
            }


            $sql = "SELECT * FROM `tbl_customer_customer` 
                            WHERE `customer_phone` = '{$customer_phone}'
                            ";
            $result = db_qr($sql);
            $nums = db_nums($result);
            if ($nums > 0) {
                returnError("Đã tồn tại khách hàng này");
            }
            // Tạo mã khách hàng
            $customer_code ="KH" . substr(time(), -8);

            $dir_save_cert_img = handing_file_img($customer_cert_img, $dir_save_customer_cert_img);
            $dir_save_account_img = handing_file_img($customer_account_img, $dir_save_customer_account_img);
            $sql = "INSERT INTO `tbl_customer_customer` SET 
                                                `customer_fullname` = '{$customer_name}',
                                                `customer_code` = '{$customer_code}',
                                                `customer_phone` = '{$customer_phone}'
                                                ";
            if (isset($customer_introduce) && !empty($customer_introduce)) {
                $sql .= " ,`customer_introduce` = '{$customer_introduce}'";
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
