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
    case 'list_advertisement':
        include_once "./viewlist_board/list_advertisement.php";
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

        if (isset($_REQUEST['advertisement_title'])) {   //*
            if ($_REQUEST['advertisement_title'] == '') {
                unset($_REQUEST['advertisement_title']);
                returnError("Nhập tiêu đề khuyễn mãi");
            } else {
                $advertisement_title = $_REQUEST['advertisement_title'];
            }
        } else {
            returnError("Nhập tiêu đề khuyễn mãi");
        }

        if (isset($_REQUEST['advertisement_content'])) {   //*
            if ($_REQUEST['advertisement_content'] == '') {
                unset($_REQUEST['advertisement_content']);
                returnError("Nhập nội dung khuyễn mãi");
            } else {
                $advertisement_content = $_REQUEST['advertisement_content'];
            }
        } else {
            returnError("Nhập nội dung khuyễn mãi");
        }

        $sql = "SELECT * FROM tbl_advertisement";
        $result = db_qr($sql);
        if (db_nums($result) > 0) {
            returnError("Đã tồn tại khuyễn mãi");
        }

        $sql = "INSERT INTO `tbl_advertisement` SET 
                            `id_account` = '{$id_account}',
                            `advertisement_title` = '{$advertisement_title}',
                            `advertisement_content` = '{$advertisement_content}'
                            ";
        if (mysqli_query($conn, $sql)) {
            returnSuccess("Tạo khuyễn mãi thành công");
        } else {
            returnError("Lỗi tạo khuyễn mãi");
        }
        break;

    case 'update':
        if (isset($_REQUEST['id_advertisement'])) {   //*
            if ($_REQUEST['id_advertisement'] == '') {
                unset($_REQUEST['id_advertisement']);
                returnError("Nhập id_advertisement");
            } else {
                $id_advertisement = $_REQUEST['id_advertisement'];
            }
        } else {
            returnError("Nhập id_advertisement");
        }

        $success = array();
        if (isset($_REQUEST['id_account']) && !empty($_REQUEST['id_account'])) { //*
            $id_account = $_REQUEST['id_account'];
            $sql = "UPDATE `tbl_advertisement` SET";
            $sql .= " `id_account` = '{$id_account}'";
            $sql .= " WHERE `id` = '{$id_advertisement}'";

            if (mysqli_query($conn, $sql)) {
                $success['id_account'] = "true";
            }
        }

        if (isset($_REQUEST['advertisement_title']) && !empty($_REQUEST['advertisement_title'])) { //*
            $advertisement_title = $_REQUEST['advertisement_title'];
            $sql = "UPDATE `tbl_advertisement` SET";
            $sql .= " `advertisement_title` = '{$advertisement_title}'";
            $sql .= " WHERE `id` = '{$id_advertisement}'";

            if (mysqli_query($conn, $sql)) {
                $success['advertisement_title'] = "true";
            }
        }

        if (isset($_REQUEST['advertisement_content']) && !empty($_REQUEST['advertisement_content'])) { //*
            $advertisement_content = $_REQUEST['advertisement_content'];
            $sql = "UPDATE `tbl_advertisement` SET";
            $sql .= " `advertisement_content` = '{$advertisement_content}'";
            $sql .= " WHERE `id` = '{$id_advertisement}'";

            if (mysqli_query($conn, $sql)) {
                $success['advertisement_content'] = "true";
            }
        }

        if (!empty($success)) {
            returnSuccess("Cập nhật thành công");
        } else {
            returnError("Không có thông tin cập nhật");
        }

        break;



    case 'delete':
        if (isset($_REQUEST['id_advertisement'])) {   //*
            if ($_REQUEST['id_advertisement'] == '') {
                unset($_REQUEST['id_advertisement']);
                returnError("Nhập id_advertisement");
            } else {
                $id_advertisement = $_REQUEST['id_advertisement'];
            }
        } else {
            returnError("Nhập id_advertisement");
        }

        $sql = "DELETE FROM tbl_advertisement WHERE id = '$id_advertisement'";
        if(db_qr($sql)){
            returnSuccess("Xoá thành công");
        }else{
            returnSuccess("Lỗi xóa khuyễn mãi");
        }

        break;


    default:
        returnError("type_manager is not accept!");
        break;
}
