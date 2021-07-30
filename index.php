<?php
session_start();
require_once '../__required/db_connect.php';
if (isset($_SESSION["Sub_Admin_ID"])) {
    $Sub_Admin_ID = $_SESSION["Sub_Admin_ID"];
    $date = date("Y-m-d");
    $hour_now = date("H");
    // auto logout after 30 min of inactivity
    if (time() - $_SESSION['Last_login_timestamp'] > 300) { // 5 * 60 = 300 min
        header("location: ./logout.php"); //redirect to index.php
        exit;
    } else {
        $_SESSION['Last_login_timestamp'] = time(); //set new Last_login_timestamp
    }
    // end
} else {
    header("location:../");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="./assets/css/messages.css">
    <script src="./assets/js/jquery-3.6.0.min.js"></script>
</head>

<body>
    <li><a href="../">Back</a></li>
    <div class="shadow" id="shadow"></div>
    <div class="t_head">
        <h2>Messages</h2>
    </div>
    <div class="body">
        <div id="msg_list">
            <!-- <a href="">
                <div class="user">
                    <p>Lorem ipsum dolo</p>
                </div>
            </a> -->
        </div>
    </div>
    <script>
        $(document).ready(function() {
            function loadMsg() {
                $.ajax({
                    type: "POST",
                    url: "./conversations.php",
                    data: {
                        Sub_Admin_ID: <?php echo $Sub_Admin_ID; ?>
                    },
                    success: function(data) {
                        if (data) {
                            $("#msg_list").html(data);
                        } else {
                            console.log("no data loaded");
                        }
                    }
                });
            }
            loadMsg();
            setInterval(function() {
                loadMsg();
            }, 5000);
        })
    </script>
</body>

</html>