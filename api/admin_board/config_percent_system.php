<?php
$percent_status = "N";
if (isset($_REQUEST['percent_status']) && !empty($_REQUEST['percent_status'])) {
    $percent_status = $_REQUEST['percent_status'];
}

$percent_system = '0';
if (isset($_REQUEST['percent_system']) && !empty($_REQUEST['percent_system'])) {
    $percent_system = $_REQUEST['percent_system'];
}

if ($percent_status == "N") {
    $percent_system = '0';
}
$sql = "UPDATE tbl_percent_system SET
        percent_status = '$percent_status',
        percent_system = '$percent_system'
        WHERE id = '1'
        ";
if (db_qr($sql)) {
    returnSuccess("Cài đặt thành công");
}
