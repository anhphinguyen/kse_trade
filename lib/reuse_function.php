<?php
function get_exchange_quantity($id_exchange)
{
    $time = date("d-m-Y", time());
    $time_begin = strtotime($time . " 00:00:00");
    $time_end = strtotime($time . " 23:59:59");
    $sql = "SELECT * FROM tbl_exchange_period 
                            WHERE period_open >= '$time_begin'
                            AND period_open <= '$time_end'
                            AND id_exchange = '" . $id_exchange . "'
                            ";
    $total = count(db_fetch_array($sql));
    return $total;
}
function get_customer_paymented_in_day($id_customer)
{
    $time = date("d-m-Y", time());
    $time_begin = strtotime($time . " 00:00:00");
    $time_end = strtotime($time . " 23:59:59");

    $sql_check_money_used = "SELECT 
                                 SUM(tbl_request_payment.request_value) as money_used
                                 FROM tbl_request_payment
                                 LEFT JOIN tbl_customer_customer ON tbl_customer_customer.id = tbl_request_payment.id_customer
                                 WHERE tbl_request_payment.id_customer = '$id_customer'
                                 AND tbl_request_payment.request_created >= '$time_begin'
                                 AND tbl_request_payment.request_created <= '$time_end'
                                 GROUP BY tbl_request_payment.id_customer
                                ";

    $result_check_money_used  = db_qr($sql_check_money_used);
    $nums_check_money_used  = db_nums($result_check_money_used);
    if ($nums_check_money_used > 0) {
        while ($row_check_money_used = db_assoc($result_check_money_used)) {
            $customer_paymented = $row_check_money_used['money_used'];
            return $customer_paymented;
        }
    }
    // else{
    //     returnError("Lỗi truy vấn get_customer_paymented_in_day");
    // }  
}
function demo_add_money($id_session, $trading_type = "")
{
    $sql_win = "SELECT * FROM tbl_customer_demo_log WHERE id_exchange_period = '$id_session' AND trading_type = $trading_type";

    $result_win = db_qr($sql_win);
    $num_win = db_nums($result_win);
    if ($num_win > 0) {
        while ($row_win = db_assoc($result_win)) {
            $trading_bet = (int)$row_win['trading_bet'];
            $trading_percent = (int)$row_win['trading_percent'];
            $id_demo = $row_win['id_demo'];
            $sql_wallet = "SELECT demo_wallet_bet FROM tbl_customer_demo WHERE id = '$id_demo'";
            $result_wallet = db_qr($sql_wallet);
            $num_wallet = db_nums($result_wallet);
            if ($num_wallet > 0) {
                while ($row_wallet = db_assoc($num_wallet)) {
                    $customer_wallet_add = $row_wallet['demo_wallet_bet'] + ($trading_percent * $trading_bet) / 100;
                    $sql_add_money = "UPDATE tbl_customer_demo SET demo_wallet_bet = '$customer_wallet_add' WHERE id = 'id_demo'";
                    db_qr($sql_add_money);
                }
            }
        }
    }
}
function customer_add_money($id_session, $trading_type = "")
{
    $sql_win = "SELECT * FROM tbl_trading_log WHERE id_exchange_period = '$id_session' AND trading_type = $trading_type";
    // echo $sql_win;
    // exit();
    $result_win = db_qr($sql_win);
    $num_win = db_nums($result_win);
    if ($num_win > 0) {
        while ($row_win = db_assoc($result_win)) {
            $trading_bet = (int)$row_win['trading_bet'];
            $trading_percent = (int)$row_win['trading_percent'];
            $id_customer = $row_win['id_customer'];
            $sql_wallet = "SELECT customer_wallet_bet FROM tbl_customer_customer WHERE id = '$id_customer'";
            $result_wallet = db_qr($sql_wallet);
            $num_wallet = db_nums($result_wallet);
            if ($num_wallet > 0) {
                while ($row_wallet = db_assoc($num_wallet)) {
                    $customer_wallet_add = $row_wallet['customer_wallet_bet'] + ($trading_percent * $trading_bet) / 100;
                    $sql_add_money = "UPDATE tbl_customer_customer SET customer_wallet_bet = '$customer_wallet_add' WHERE id = 'id_customer'";
                    db_qr($sql_add_money);
                }
            }
        }
    }
}
function get_list_customer_by_level($id, $id_business, $point_arr = array(), $id_level_arr = array())
{
    $sql_customer = "SELECT `customer_point` FROM `tbl_customer_customer` WHERE `id_business` = '{$id_business}' ";
    for ($i = 0; $i < count($id_level_arr); $i++) {
        for ($j = $i + 1; $j <= count($id_level_arr); $j++) {
            if ($j == count($id_level_arr)) {
                if ($id == $id_level_arr[$i]) {
                    $point_end = (int)$point_arr[$i];
                    $sql_customer .= " AND `customer_point` >= {$point_end}
                             ";
                }
                break;
            } else {
                if ($id == $id_level_arr[$i]) {
                    $point_begin = (int)$point_arr[$i];
                    $point_end = (int)$point_arr[$j];
                    $sql_customer .= " AND `customer_point` >= {$point_begin}
                              AND `customer_point` < {$point_end}
                            ";
                }
                break;
            }
        }
    }

    return $sql_customer;
}
function arrange_position($point_arr = array(), $level_arr = array(), $id_arr = array())
{
    $tmp_point = "";
    $tmp_level = "";
    $tmp_id = "";
    $result = array();

    for ($i = 0; $i < count($point_arr); $i++) {
        for ($j = $i + 1; $j < count($point_arr); $j++) {
            if ($point_arr[$i] > $point_arr[$j]) {
                $tmp_point = $point_arr[$i];
                $point_arr[$i] = $point_arr[$j];
                $point_arr[$j] = $tmp_point;

                $tmp_level = $level_arr[$i];
                $level_arr[$i] = $level_arr[$j];
                $level_arr[$j] = $tmp_level;

                $tmp_id = $id_arr[$i];
                $id_arr[$i] = $id_arr[$j];
                $id_arr[$j] = $tmp_id;
            }
        }
    }
    $result['id_level'] = $id_arr;
    $result['point'] = $point_arr;
    $result['level'] = $level_arr;
    return $result;
}
// update product extra
function update_product_extra($id_extra_req, $id_product_extra_req, $id_product, $id_business)
{
    global $conn;
    $success = array();
    if (isset($id_extra_req) && !empty($id_extra_req)) {
        $id_extra = explode(",", $id_extra_req);
        if (isset($id_product_extra_req) && !empty($id_product_extra_req)) {
            $id_product_extra = explode(",", $id_product_extra_req);

            if (count($id_product_extra) >= count($id_extra)) {
                while (count($id_extra) < count($id_product_extra)) {
                    array_push($id_extra, "null");
                }
                for ($i = 0; $i < count($id_extra); $i++) {
                    if ($id_extra[$i] == "null") {
                        $sql = "INSERT INTO `tbl_product_extra` 
                                        SET `id_product_extra` = '{$id_product_extra[$i]}',
                                            `id_product` = '{$id_product}',
                                            `id_business` = '{$id_business}'
                                            ";
                        if (mysqli_query($conn, $sql)) {
                            $success['add_id_product_extra'] = 'true';
                        }
                    } else {
                        $sql = "UPDATE `tbl_product_extra` 
                                        SET `id_product_extra` = '{$id_product_extra[$i]}'
                                        WHERE `id` = '{$id_extra[$i]}'";
                        if (mysqli_query($conn, $sql)) {
                            $success['edit_id_product_extra'] = 'true';
                        }
                    }
                }
            } else {

                while (count($id_product_extra) < count($id_extra)) {
                    array_push($id_product_extra, 0);
                }
                for ($i = 0; $i < count($id_extra); $i++) {

                    $sql = "    UPDATE `tbl_product_extra` 
                                        SET `id_product_extra` = '{$id_product_extra[$i]}'
                                        WHERE `id` = '{$id_extra[$i]}'";
                    if (mysqli_query($conn, $sql)) {
                        $success['edit_id_product_extra'] = 'true';
                    }
                }

                $sql = "DELETE FROM `tbl_product_extra` WHERE `id_product_extra` = '0'";
                db_qr($sql);
            }
        } else {
            for ($i = 0; $i < count($id_extra); $i++) {
                $sql = "DELETE FROM `tbl_product_extra` 
                                WHERE `id` = '{$id_extra[$i]}'";
                if (mysqli_query($conn, $sql)) {
                    $success['del_id_product_extra'] = 'true';
                }
            }
        }
    } else {
        if (isset($id_product_extra_req) && !empty($id_product_extra_req)) {
            $id_product_extra = explode(",", $id_product_extra_req);
            foreach ($id_product_extra as $item) {
                if (!empty($item)) {
                    $sql = "INSERT INTO `tbl_product_extra` SET 
                                    `id_product_extra` = '{$item}',
                                    `id_product` = '{$id_product}',
                                    `id_business` = '{$id_business}'
                                    ";
                    if (mysqli_query($conn, $sql)) {
                        $success['add_id_product_extra'] = 'true';
                    }
                }
            }
        }
    }
    if (!empty($success)) {
        return true;
    } else {
        return false;
    }
}
// xu ly hinh anh

function handing_files_img($myfile, $dir_save)
{   // myfile = file nhập vào, $max_size = kích thước lớn nhất của file, 
    // $allow_file_type = các đuôi file cho phép, $dir_save = thư mục lưu trữ
    $total = count($_FILES[$myfile]['name']);
    for ($i = 0; $i < $total; $i++) {
        if ($_FILES[$myfile]['error'][$i] == 0) {
            $target_dir = $dir_save;
            $target_dir_4_upload = '../' . $dir_save;
            $target_file = $target_dir . basename($_FILES[$myfile]['name'][$i]);
            $target_save_file = $target_dir_4_upload . basename($_FILES[$myfile]['name'][$i]);

            $allow_file_type = array('jpg', 'jpeg', 'png');
            $max_file_size = 5242880;
            $img_file_type = pathinfo($target_file, PATHINFO_EXTENSION);

            // kiem tra co phai file anh
            $check = getimagesize($_FILES[$myfile]['tmp_name'][$i]);
            if ($check !== false) {
                $img_info = pathinfo($_FILES[$myfile]['name'][$i]);
                if (file_exists($target_save_file)) {
                    $k = 0;
                    $name_copy = $img_info['filename'] . "_Copy_" . $k;
                    $target_file = $target_dir . $name_copy . "." . $img_info['extension'];
                    $target_save_file = $target_dir_4_upload . $name_copy . "." . $img_info['extension'];
                    while (file_exists($target_save_file)) {
                        $k++;
                        $name_copy = $img_info['filename'] . "_Copy_" . $k;
                        $target_file = $target_dir . $name_copy . "." . $img_info['extension'];
                        $target_save_file = $target_dir_4_upload . $name_copy . "." . $img_info['extension'];
                    }
                }

                if ($_FILES[$myfile]['size'][$i] > $max_file_size) {
                    return_error("file size is greater than {$max_file_size}");
                }

                if (!in_array(strtolower($img_file_type), $allow_file_type)) {
                    return_error("file type is not allow, {$allow_file_type}");
                }

                move_uploaded_file($_FILES[$myfile]['tmp_name'][$i], $target_save_file);

                $file[] = $target_file;
            } else {
                return_error("Không phải ảnh");
            }
        } else {

            return_error("Lỗi dữ liệu");
        }
    }
    if (isset($file) && !empty($file)) {
        return $file;
    }
}





function handing_file_img($myfile, $dir_save)
{    // myfile = file nhập vào, $max_size = kích thước lớn nhất của file, 
    // $allow_file_type = các đuôi file cho phép, $dir_save = thư mục lưu trữ
    if ($_FILES[$myfile]['error'] == 0) {
        $target_dir = $dir_save;
        $target_dir_4_upload = '../' . $dir_save;
        $target_file = $target_dir . basename($_FILES[$myfile]['name']);
        $target_save_file = $target_dir_4_upload . basename($_FILES[$myfile]['name']);


        $allow_file_type = array('jpg', 'jpeg', 'png');
        $max_file_size = 5242880;
        $img_file_type = pathinfo($target_file, PATHINFO_EXTENSION);

        // kiem tra co phai file anh
        $check = getimagesize($_FILES[$myfile]['tmp_name']);
        if ($check !== false) {
            $img_info = pathinfo($_FILES[$myfile]['name']);
            if (file_exists($target_save_file)) {
                $k = 0;
                $name_copy = $img_info['filename'] . "_Copy_" . $k;
                $target_file = $target_dir . $name_copy . "." . $img_info['extension'];
                $target_save_file = $target_dir_4_upload . $name_copy . "." . $img_info['extension'];
                while (file_exists($target_save_file)) {
                    $k++;
                    $name_copy = $img_info['filename'] . "_Copy_" . $k;
                    $target_file = $target_dir . $name_copy . "." . $img_info['extension'];
                    $target_save_file = $target_dir_4_upload . $name_copy . "." . $img_info['extension'];
                }
            }

            if ($_FILES[$myfile]['size'] > $max_file_size) {
                return_error("file size is greater than {$max_file_size}");
            }

            if (!in_array(strtolower($img_file_type), $allow_file_type)) {
                return_error("file type is not allow, {$allow_file_type}");
            }

            move_uploaded_file($_FILES[$myfile]['tmp_name'], $target_save_file);
            // return_success($target_file);
            return $target_file;
        } else {
            return_error("Không phải ảnh");
        }
    } else {
        return_error("Lỗi dữ liệu");
    }
}

// function check_img_name($target_save_file, $img_info, $target_dir, $target_dir_4_upload){
//     if (file_exists($target_save_file)) {
//         $k = 0;
//         $name_copy = $img_info['filename'] . "_Copy_" . $k;
//         $target_file = $target_dir . $name_copy . "." . $img_info['extension'];
//         $target_save_file = $target_dir_4_upload . $name_copy . "." . $img_info['extension'];
//         while (file_exists($target_save_file)) {
//             $k++;
//             $name_copy = $img_info['filename'] . "_Copy_" . $k;
//             $target_file = $target_dir . $name_copy . "." . $img_info['extension'];
//             $target_save_file = $target_dir_4_upload . $name_copy . "." . $img_info['extension'];
//         }
//     }
// }

function db_assoc($result)
{
    return mysqli_fetch_assoc($result);
}
function db_nums($result)
{
    $nums = mysqli_num_rows($result);
    return $nums;
}

function db_qr($sql)
{
    global $conn;
    $result = mysqli_query($conn, $sql);
    if (!empty($result)) {
        return $result;
    }
    return false;
}

function errorToken($error_code, $msg = "", $data = array())
{
    echo json_encode(array(
        'success' => ($error_code == '4001') ? 'true' : 'false',
        'message' => $msg,
        'error_code' => $error_code,
        'data' => $data
    ));
    exit();
}

function return_error($message)
{
    echo json_encode(array(
        'success' => 'false',
        'message' => $message,
    ));
    exit();
}

function reJson($array)
{
    echo json_encode($array);
    exit();
}
function returnError($string)
{
    echo json_encode(
        array('success' => 'false', 'message' => $string)
    );
    exit();
}
function returnSuccess($string)
{
    echo json_encode(
        array('success' => 'true', 'message' => $string)
    );
    exit();
}

function getRolePermission($idUser = '')
{
    global $conn;
    $sql = "SELECT * FROM tbl_account_permission";

    if (!empty($idUser)) {
        $sql = " SELECT 
            tbl_account_permission.id,
            tbl_account_permission.permission,
            tbl_account_permission.description

            FROM tbl_account_permission
            LEFT JOIN tbl_account_authorize
            ON tbl_account_permission.id = tbl_account_authorize.grant_permission

            WHERE tbl_account_authorize.id_admin = '" . $idUser . "'
			
			ORDER BY tbl_account_authorize.grant_permission ASC
        ";
    }

    $result = mysqli_query($conn, $sql);
    // mysqli_close($conn);
    // Get row count
    $num = mysqli_num_rows($result);
    $arr_result = array();
    // Check if any item
    if ($num > 0) {

        while ($row = $result->fetch_assoc()) {

            $role_item = array(
                'id' => $row['id'],
                'permission' => $row['permission'],
                'description' => $row['description']
            );
            // Push to "data"
            array_push($arr_result, $role_item);
        }
    }

    return $arr_result;
}

function saveImage($file, $target_save = '')
{
    $link_image = '';
    if (isset($file) && is_uploaded_file($file['tmp_name'])) {
        // check file size (1048576: 1MB) 5242880

        if ($file['size'] >= 5242880) {
            //  returnError("only accept file size < 5MB!");

            return "error_size_img";
        }

        // check file type
        $allowedTypes = array(
            IMAGETYPE_PNG,
            IMAGETYPE_JPEG,
            IMAGETYPE_GIF
        );
        $detectedType = exif_imagetype($file['tmp_name']);
        $error = !in_array($detectedType, $allowedTypes);

        if ($error) {
            //returnError("only accept PNG, JPEG, GIF !");
            return "error_type_img";
        }

        $target_dir = $target_save;
        $target_dir_4_upload = '../' . $target_save;
        $final_name = basename($file["name"]);

        $path = $file['name'];
        $ext = pathinfo($path, PATHINFO_EXTENSION);
        $final_name = generateRandomString(60) . '.' . $ext;

        // end handle way to rename

        while (file_exists($target_dir_4_upload . $final_name)) {
            // doi ten file
            $final_name = generateRandomString(60) . '.' . $ext;
        }

        // upload file toi folder icon
        $target_file_upload = $target_dir_4_upload . $final_name;
        $target_file = $target_dir . $final_name;

        move_uploaded_file($file["tmp_name"], $target_file_upload);

        $link_image = $target_file;
    }

    return $link_image;
}
function generateRandomString($length)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function pushNotification($title, $message, $action, $to, $type_send = 'topic', $server_key = 'AAAA0MM4ZB0:APA91bFYwE1Pusx0ftTdqeK3yKoiRU0DmhANyeYx4m74oQbP8UNnwP71c1K4PYmoszje9cl9SthdlyCy30KS1qTV8i088DGcA5jkacav0x56vO8mKldD6kxqIsNliORhtAdZfmepWQyj')
{
    $message_data = array(
        'title' => $title,
        'body' => $message,
        "click_action" => $action,
        "badge" => "1"
    );
    $headers = array(
        'Authorization: key=' . $server_key,
        'Content-Type: application/json'
    );

    $data = array();

    if (!empty($type_send) && $type_send == 'single') {
        require_once 'notification.php';
        $notification = new Notification();

        $notification->setTitle($title);
        $notification->setMessage($message);
        $notification->setAction($action);

        $requestData = $notification->getNotificatin();

        $data['to'] = $to;
        $data['data'] = $requestData;
    } else {
        $data['to'] = "/topics/" . $to;
        $data['notification'] = $message_data;
    }

    $data = json_encode($data);

    // print_r($data);
    // exit

    $url = 'https://fcm.googleapis.com/fcm/send';

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    $result = curl_exec($ch);
    curl_close($ch);
}
