<?php
session_start();
require_once '../../__required/db_connect.php';
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

    if (isset($_GET["id"])) {
        $get_cid = mysqli_real_escape_string($conn, $_GET["id"]);
        $cid = (int) filter_var($get_cid, FILTER_SANITIZE_NUMBER_INT);
        if (!empty($cid)) {
            // 
        } else {
            header("location:../");
        }
    } else {
        header("location:../");
    }
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
    <title>Conversation</title>
    <script src="https://bbdonlinetraining.com/en/assets/js/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: "Roboto", "system-ui", -apple-system, BlinkMacSystemFont,
                "Segoe UI", Helvetica, Arial, sans-serif, "Apple Color Emoji",
                "Segoe UI Emoji", "Segoe UI Symbol";
        }

        #messages {
            min-height: 200px;
            height: 560px;
            overflow: auto;
            max-width: 100vw;
            padding: 10px 30px 20px 30px;
            background: gray;
        }

        #messages .outgoing,
        #messages .incoming {
            display: flex;
        }

        #messages .chat {
            margin: 10px 0;
        }

        #messages .chat .msg_text p {
            word-wrap: auto;
            overflow-wrap: anywhere;
            padding: 10px 16px;
        }



        #messages .outgoing .msg_text {
            display: flex;
            flex-direction: column;
            margin-left: auto;
            max-width: calc(100% - 130px);
        }

        #messages .incoming .msg_text {
            margin-right: auto;
            max-width: calc(100% - 130px);
        }

        #messages .outgoing .msg_text p {
            background: #333;
            color: #fff;
            border-radius: 18px 18px 0 18px;
        }

        #messages .incoming .msg_text p {
            background: white;
            color: black;
            border-radius: 18px 18px 18px 0;
        }

        #messages .msg_text p a {
            color: green;
        }

        #messages .outgoing .msg_text span,
        #messages .incoming .msg_text span {
            align-self: flex-end;
            font-size: 12px;
            color: black;
        }

        h2 em {
            position: relative;
            font-size: 38px;
            top: 7px;
            left: 20px;
            margin-right: 30px;
            cursor: pointer;
        }

        #send_msg #msg {
            height: 45px;
            width: calc(100% - 140px);
            font-size: 17px;
            border: 1px solid #ccc;
            padding: 0 13px;
            margin-left: 10px;
            border-radius: 5px 0 0 5px;
            outline: none;
        }

        #send_msg #send {
            width: 85px;
            height: 47px;
            border: none;
            outline: none;
            background: #333;
            color: #fff;
            font-size: 19px;
            cursor: pointer;
            border-radius: 0 5px 5px 0;
        }
    </style>
</head>

<body>
    <h2><em class="fas fa-angle-left" onclick="location.href='../'"></em> <?php echo $cid; ?></h2>
    <div id="messages">
        <!-- <div class='chat outgoing'>
            <div class='msg_text'>
                <p>&nbsp;<a href="http://google.com" target="_blank">http://google.com</a>&nbsp; and &nbsp;&nbsp;<a href="http://facebook.com" target="_blank">http://facebook.com</a>&nbsp;this 2 is my wsute &nbsp;</p>
                <span> 20:50</span>
            </div>
        </div> -->
    </div>
    </div>
    <hr>
    <div id="replay">
        <form id="send_msg">
            <input type="text" name="msg" id="msg" placeholder="type your message.." required>
            <button type="submit" name="send" id="send">Send <em class='fab fa-telegram-plane'></em></button>
        </form>
    </div>
    <script>
        $(document).ready(function() {
            function loadMsgText() {
                $.ajax({
                    type: "POST",
                    url: "./get_msg.php",
                    data: {
                        Sub_Admin_ID: <?php echo $Sub_Admin_ID; ?>,
                        cid: <?php echo $cid; ?>
                    },
                    success: function(data) {
                        if (data) {
                            $("#messages").html(data);
                            $("#messages").animate({
                                scrollTop: $("#messages")[0].scrollHeight
                            });
                        } else {
                            console.log("no data loaded");
                        }

                    }

                });
            }
            loadMsgText();

            $("#send_msg").on("submit", function(e) {
                e.preventDefault();
                let type = $("#msg").val();
                $.ajax({
                    type: "POST",
                    url: "./send_msg.php",
                    data: {
                        Sub_Admin_ID: <?php echo $Sub_Admin_ID; ?>,
                        cid: <?php echo $cid; ?>,
                        new_text: type
                    },
                    success: function(data) {
                        if (data == 1) {
                            // console.log("message sent");
                            $("#msg").val("");
                            loadMsgText();
                            $("#messages").animate({
                                scrollTop: $("#messages")[0].scrollHeight
                            });
                        } else {
                            console.log("error sending messages");
                        }
                    }
                });
            })
            setInterval(function() {
                function loadMsgInterval() {
                    $.ajax({
                        type: "POST",
                        url: "./get_msg.php",
                        data: {
                            Sub_Admin_ID: <?php echo $Sub_Admin_ID; ?>,
                            cid: <?php echo $cid; ?>
                        },
                        success: function(data) {
                            if (data) {
                                $("#messages").html(data);
                            } else {
                                console.log("no data loaded");
                            }

                        }

                    });
                }
                loadMsgInterval();
            }, 10000);
        })
    </script>
</body>

</html>