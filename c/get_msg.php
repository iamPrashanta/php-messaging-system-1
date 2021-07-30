<?php
require_once '../../__required/db_connect.php';
$Login_ID = $_POST["Login_ID"];
$cid = $_POST["cid"];
$msg_text = "";

$query = mysqli_query($msgconn, "SELECT *  FROM `messages` 
WHERE (`msg_to` = $Login_ID AND `msg_from`= $cid) OR (`msg_to` = $cid AND `msg_from`= $Login_ID) ORDER BY `id` ASC LIMIT 100;");

if (mysqli_num_rows($query) > 0) {
    while ($rows = mysqli_fetch_assoc($query)) {
        $id = $rows["id"];
        $msg_to = $rows["msg_to"];
        $msg_from = $rows["msg_from"];
        $msg_body = $rows["msg_body"];
        $date_time = $rows["date_time"];
        $msg_read = $rows["msg_read"];
        $new_date_time = date("H:i", strtotime($date_time));

        $single_url = "/(http|https):\/\/[a-zA-Z\.]+\.[a-zA-Z]{2,3}\/[a-zA-Z\-\_]+/";
        if (preg_match_all($single_url, $msg_body, $matches)) {
            foreach ($matches[0] as $i => $match) {
                $msg_body = str_replace(
                    $match,
                    '&nbsp;<a href="' . $matches[0][$i]  . '" rel="nofollow">' . $matches[0][$i]  . '</a>&nbsp;',
                    $msg_body
                );
            }
        }

        $youtube = "/^((?:https?:)?\/\/)?((?:www|m)\.)?((?:youtube\.com|youtu.be))(\/(?:[\w\-]+\?v=|embed\/|v\/)?)([\w\-]+)(\S+)?$/";
        if (preg_match_all($youtube, $msg_body, $matches2)) {
            foreach ($matches2[0] as $i => $match) {
                if (strlen($matches2[0][$i]) > 27) {
                    $msg_body = str_replace(
                        $match,
                        '&nbsp;<a href="' . $matches2[1][$i] . $matches2[2][$i] . $matches2[3][$i] . $matches2[4][$i] . $matches2[5][$i] . '" rel="nofollow">' . $matches2[1][$i] . $matches2[2][$i] . $matches2[3][$i] . $matches2[4][$i] . $matches2[5][$i] . '</a>&nbsp;',
                        $msg_body
                    );
                }
            }
        }

        if ($msg_from == $Login_ID) {
            if ($msg_read == 0) {
                $msg_text .= "<div class='chat outgoing'>
                    <div class='msg_text'>
                        <p>$msg_body  &nbsp;</p>
                        <span> $new_date_time unread</span>
                        </div>
                    </div>";
            } else {
                $msg_text .= "<div class='chat outgoing'>
                    <div class='msg_text'>
                        <p>$msg_body  &nbsp;</p>
                        <span> $new_date_time read</span>
                        </div>
                    </div>";
            }
        } else {
            $update_message_read = mysqli_query($msgconn, "UPDATE `messages` SET `msg_read`= 1 WHERE `id`=$id;");
            $msg_text .= "<div class='chat incoming'>
                    <div class='msg_text'>
                        <p>$msg_body  &nbsp;</p>
                        <span>  $new_date_time</span>
                    </div>
                </div>";
        }
    }
    mysqli_close($msgconn);
    echo $msg_text;
} else {
    echo "No messagess";
}
