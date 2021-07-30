<?php
require_once '../__required/db_connect.php';
$Sub_Admin_ID = $_POST["Sub_Admin_ID"];
$msg_from_list = mysqli_query($msgconn, "SELECT DISTINCT `msg_from`, `msg_to` FROM `messages` WHERE `msg_to` = $Sub_Admin_ID OR msg_from = $Sub_Admin_ID;");

$list = "<table>";
if (mysqli_num_rows($msg_from_list) > 0) {
    while ($rows = mysqli_fetch_assoc($msg_from_list)) {
        $msg_from = $rows["msg_from"];
        $msg_to = $rows["msg_to"];
        if ($Sub_Admin_ID == $msg_from) {
            $list .= "<a href='./c/?id=$msg_to'>
            <div class='user'>
                <p>to ->: $msg_to</p>
                </div>
            </a>";
        } else {
            $get_new_message = mysqli_num_rows(mysqli_query($msgconn, "SELECT *  FROM `messages` 
            WHERE `msg_from`= '$msg_from' AND `msg_to`='$Sub_Admin_ID' AND `msg_read`='0';"));
            // while ($new_msg = mysqli_fetch_assoc($get_new_message))
            if ($get_new_message >= 1) {
                $list .= "<a href='./c/?id=$msg_from'>
                    <div class='user'>
                    <p>from ->: $msg_from ($get_new_message new messages)</p>
                    </div>
                    </a>";
            } else {
                $list .= "<a href='./c/?id=$msg_from'>
                    <div class='user'>
                    <p>from ->: $msg_from</p>
                    </div>
                    </a>";
            }
        }
    }
    $list .= "</table>";
    echo $list;
} else {
    echo "No messages";
}
