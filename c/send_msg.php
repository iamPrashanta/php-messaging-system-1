<?php
require_once '../../__required/db_connect.php';
$date_time = date("Y-m-d H:i:s");
$Sub_Admin_ID = $_POST["Sub_Admin_ID"];
$cid = $_POST["cid"];
$new_text = mysqli_real_escape_string($msgconn, $_POST["new_text"]);
$insert_new = mysqli_query($msgconn, "INSERT INTO `messages` (`msg_from`,`msg_to`,`msg_body`,`msg_read`,`date_time`)VALUES('$Sub_Admin_ID','$cid','$new_text',0,'$date_time');");
if ($insert_new == 1) {
    echo 1;
} else {
    echo 0;
}
