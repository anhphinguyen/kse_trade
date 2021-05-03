<?php
if (isset($_REQUEST['type_manager'])) {
    if ($_REQUEST['type_manager'] == '') {
        unset($_REQUEST['type_manager']);
    }
}

if (!isset($_REQUEST['type_manager'])) {
    returnError("type_manager is missing!");
}

$typeManager = $_REQUEST['type_manager'];

switch ($typeManager) {
    case 'list_notify':
        include_once "./viewlist_board/get_notify.php";
        break;

    case 'create':
        if (isset($_REQUEST['id_account'])) {   //*
            if ($_REQUEST['id_account'] == '') {
                unset($_REQUEST['id_account']);
                returnError("Nhập id_account");
            } else {
                $id_account = $_REQUEST['id_account'];
            }
        } else {
            returnError("Nhập id_account");
        }

        if (isset($_REQUEST['popup_title'])) {   //*
            if ($_REQUEST['popup_title'] == '') {
                unset($_REQUEST['popup_title']);
                returnError("Nhập tiêu đề thông báo");
            } else {
                $popup_title = $_REQUEST['popup_title'];
            }
        } else {
            returnError("Nhập tiêu đề thông báo");
        }

        if (isset($_REQUEST['popup_content'])) {   //*
            if ($_REQUEST['popup_content'] == '') {
                unset($_REQUEST['popup_content']);
                returnError("Nhập nội dung thông báo");
            } else {
                $popup_content = $_REQUEST['popup_content'];
            }
        } else {
            returnError("Nhập nội dung thông báo");
        }

        $sql = "SELECT * FROM tbl_popup_popup";
        $result = db_qr($sql);
        if (db_nums($result) > 0) {
            returnError("Đã tồn tại thông báo");
        }

        $sql = "INSERT INTO `tbl_popup_popup` SET 
                            `id_account` = '{$id_account}',
                            `popup_title` = '{$popup_title}',
                            `popup_content` = '{$popup_content}'
                            ";
		
        if (mysqli_query($conn, $sql)) {
            returnSuccess("Tạo thông báo thành công");
        } else {
            returnError("Lỗi tạo thông báo");
        }
        break;

    case 'update':
        if (isset($_REQUEST['id_popup'])) {   //*
            if ($_REQUEST['id_popup'] == '') {
                unset($_REQUEST['id_popup']);
                returnError("Nhập id_popup");
            } else {
                $id_popup = $_REQUEST['id_popup'];
            }
        } else {
            returnError("Nhập id_popup");
        }

        $success = array();
        if (isset($_REQUEST['id_account']) && !empty($_REQUEST['id_account'])) { //*
            $id_account = $_REQUEST['id_account'];
            $sql = "UPDATE `tbl_popup_popup` SET";
            $sql .= " `id_account` = '{$id_account}'";
            $sql .= " WHERE `id` = '{$id_popup}'";

            if (mysqli_query($conn, $sql)) {
                $success['id_account'] = "true";
            }
        }

        if (isset($_REQUEST['popup_title']) && !empty($_REQUEST['popup_title'])) { //*
            $popup_title = $_REQUEST['popup_title'];
            $sql = "UPDATE `tbl_popup_popup` SET";
            $sql .= " `popup_title` = '{$popup_title}'";
            $sql .= " WHERE `id` = '{$id_popup}'";

            if (mysqli_query($conn, $sql)) {
                $success['popup_title'] = "true";
            }
        }

        if (isset($_REQUEST['popup_content']) && !empty($_REQUEST['popup_content'])) { //*
            $popup_content = $_REQUEST['popup_content'];
            $sql = "UPDATE `tbl_popup_popup` SET";
            $sql .= " `popup_content` = '{$popup_content}'";
            $sql .= " WHERE `id` = '{$id_popup}'";

            if (mysqli_query($conn, $sql)) {
                $success['popup_content'] = "true";
            }
        }

        if (!empty($success)) {
            returnSuccess("Cập nhật thành công");
        } else {
            returnError("Không có thông tin cập nhật");
        }

        break;



    case 'delete':
        if (isset($_REQUEST['id_popup'])) {   //*
            if ($_REQUEST['id_popup'] == '') {
                unset($_REQUEST['id_popup']);
                returnError("Nhập id_popup");
            } else {
                $id_popup = $_REQUEST['id_popup'];
            }
        } else {
            returnError("Nhập id_popup");
        }

        $sql = "DELETE FROM tbl_popup_popup WHERE id = '$id_popup'";
        if(db_qr($sql)){
            returnSuccess("Xoá thành công");
        }else{
            returnSuccess("Lỗi xóa thông báo");
        }

        break;


    default:
        returnError("type_manager is not accept!");
        break;
}
