<?php
$type_manager = '';

if (isset($_REQUEST['type_manager']) && $_REQUEST['type_manager'] != '') {
    $type_manager = $_REQUEST['type_manager'];

    //chon type_mânger
    switch ($type_manager) {
            // check role
        case "check_role": {

                if (isset($_REQUEST['id_user']) && !empty($_REQUEST['id_user'])) {
                    $id_user = $_REQUEST['id_user'];
                } else {
                    returnError("Nhập id_user");
                }

                $sql = "SELECT 
                            `tbl_account_account`.`id` as `id_account`,
                            `tbl_account_account`.`id_type` as `id_type`,
                            `tbl_account_account`.`account_username` as `account_username`,
                            `tbl_account_account`.`account_password` as `account_password`,
                            `tbl_account_account`.`account_fullname` as `account_fullname`,
                            `tbl_account_account`.`account_email` as `account_email`,
                            `tbl_account_account`.`account_phone` as `account_phone`,
                            `tbl_account_account`.`account_status` as `account_status`,
                            `tbl_account_account`.`account_token` as `account_token`, -- chưa bổ sung vào DB
                            `tbl_account_account`.`force_sign_out` as `force_sign_out`,

                            `tbl_account_type`.`type_account` as `type_account`,
                            `tbl_account_type`.`description` as `type_description`
                            FROM `tbl_account_account`

                            LEFT JOIN `tbl_account_type` ON `tbl_account_type`.`id` = `tbl_account_account`.`id_type`
                            WHERE `tbl_account_account`.`id` = '{$id_user}' 
                            ";
                $result = db_qr($sql);
                $nums = db_nums($result);
                if ($nums > 0) {
                    $user_arr = array();
                    $user_arr['success'] = 'true';
                    $user_arr['data'] = array();
                    while ($row = db_assoc($result)) {

                        $user_item = array(
                            'id' => $row['id_account'],
                            'id_type' => $row['id_type'],
                            'account_username' => $row['account_username'],
                            'account_fullname' => $row['account_fullname'],
                            'account_email' => $row['account_email'],
                            'account_phone' => $row['account_phone'],
                            'account_status' => $row['account_status'],
                            'type_account' => $row['type_account'],
                            'type_description' => $row['type_description'],
                            'account_token' => $row['account_token'],
                        );

                        if ($row['id_type'] == '1') {
                            $user_item['role_permission'] = getRolePermission($row['id_account']);
                        }

                        array_push($user_arr['data'], $user_item);
                    }
                    reJson($user_arr);
                } else {
                    returnSuccess("Không tìm thấy user");
                }
            }
            //list module
        case "list_module":
            $sql = "SELECT * FROM `tbl_account_permission`";
            $result = $conn->query($sql);
            // Get row count
            $num = mysqli_num_rows($result);

            $module_arr['success'] = 'true';
            $module_arr['data'] = array();
            if ($num > 0) {
                while ($row = $result->fetch_assoc()) {
                    $module_item = array(
                        'id' => $row['id'],
                        'permission' => $row['permission'],
                        'description' => $row['description']
                    );
                    // Push to "data"
                    array_push($module_arr['data'], $module_item);
                }
                echo json_encode($module_arr);
            } else {
                returnError("Không tìm thấy module");
            }
            break;

            //update module
        case "update_module":
            $description = '';
            $id_module = '';
            if (isset($_REQUEST['description'])) {
                if ($_REQUEST['description'] == '') {
                    unset($_REQUEST['description']);
                } else {
                    $description = $_REQUEST['description'];
                }
            }
            if (isset($_REQUEST['id_module']) && $_REQUEST['id_module'] != '') {
                $id_module = $_REQUEST['id_module'];
            } else {
                returnError("Nhập id_module");
            }
            $sql = "UPDATE tbl_account_permission SET ";
            if (!empty($description)) {
                $sql .= " description = '" . $description . "'";
            }
            $sql .= " WHERE id ='$id_module'";

            if ($conn->query($sql)) {
                returnSuccess("Cập nhật thành công!");
            } else {
                returnError("Cập nhật không thành công!");
            }

            break;

            //list type account
        case "list_type":
            $sql = "SELECT * FROM `tbl_account_type`
                        -- WHERE tbl_account_type.id_business = '$id_business'
                        WHERE 1=1
                        ";

            if (isset($_REQUEST['business_model'])) {
                if ($_REQUEST['business_model'] == '') {
                    unset($_REQUEST['business_model']);
                } else {
                    $business_model = $_REQUEST['business_model'];
                    if ($business_model == 'S') {
                        $sql .= " AND tbl_account_type.id < 3";
                    }
                }
            }
            $result = $conn->query($sql);
            // Get row count
            $num = mysqli_num_rows($result);

            $module_arr['success'] = 'true';

            $module_arr['data'] = array();
            if ($num > 0) {
                while ($row = $result->fetch_assoc()) {
                    $module_item = array(
                        'id' => $row['id'],
                        'type_account' => $row['type_account'],
                        'description' => $row['description']
                    );

                    // Push to "data"
                    array_push($module_arr['data'], $module_item);
                }
                echo json_encode($module_arr);
            } else {
                returnError("Không tìm thấy type");
            }
            break;

            //update module
        case "update_type":
            $description = '';
            $id_type = '';
            if (isset($_REQUEST['description'])) {
                if ($_REQUEST['description'] == '') {
                    unset($_REQUEST['description']);
                } else {
                    $description = $_REQUEST['description'];
                }
            }
            if (isset($_REQUEST['id_type']) && $_REQUEST['id_type'] != '') {
                $id_type = $_REQUEST['id_type'];
            } else {
                returnError("Nhập id_type");
            }
            $sql = "UPDATE tbl_account_type SET ";
            if (!empty($description)) {
                $sql .= " description = '" . $description . "'";
            }
            $sql .= " WHERE id ='$id_type'";

            if ($conn->query($sql)) {
                returnSuccess("Cập nhật thành công!");
            } else {
                returnError("Cập nhật không thành công!");
            }

            break;
        default:
            returnError("type_manager has been failed");
    }
} else {
    returnError("Chọn type_manager");
}
