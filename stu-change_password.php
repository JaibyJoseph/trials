<?php
// Include database configuration
include('db_config.php');
session_start();

if(isset($_POST['change'])) { // upon clicking the change button in stu-cp
    $std_email = $_POST['std_email']; //email from rmail i/p field in stu-change_password.php
    $new_pass = $_POST['pass']; //new password from stu-change_password file,pw input field

    $changeQuery = $con->query("UPDATE student_data SET pass = '$new_pass' WHERE std_email = '$std_email'");

    if($changeQuery) {
        header("Location: success.html");
    }
    else {
        header("Location: login.php");
        exit();}
}
$con->close();
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width-device-width", initial-scale="1">
        <title>Student Login</title>
        <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    </head>
    <style>
        .header{
            float: top;
            width: 100%;
            height: 65px;
            background-color: #43302e;
            text-align: center;
            padding-top: 18px;
        
        }
        .text1{
            position: center;
            font-size: 20px;
            text-align: center;
            font-family: "Raleway";
            font-weight: 600;
            line-height: 120%;
            text-decoration: underline;
            color: #ffffff;
        }
        .text2{
            position: center;
            font-size: 15px;
            font-weight: 600;
            color: #ffffff;
            font-family: "Raleway";
        }
        .container{
            display: flex;
            justify-content: center;
            text-align: center;
            min-height: 85vh;
            position: relative;
            margin-top: auto;
            background-size: cover;
            background: url(edited.png) no-repeat;
            background-position: center center;
        }
        .stdlogin{
            width: 20%;
            padding: 20px;
            position: absolute;
            top: 50%;
            -ms-transform: translateY(-50%);
            transform: translateY(-50%);
            margin: 0;
            border-style: solid;
            border-radius: 20px;
            background: transparent;
            backdrop-filter: blur(10px);
            box-shadow: 0 0 10px rgba(0, 0, 0, -2);
            
        }
        .container .email{
            width: 100%;
            height: 40px;
        }
        .container .new-password{
            width: 100%;
            height: 40px;
        }
        .stdlogin input{
            text-align: center;
            width: 100%;
            height: 100%;
            background: transparent;
            border-style: solid;
            border-radius: 40px;
            font-size: 16px;
            font-weight: 500;
        }
        .stdlogin input::placeholder{
            color: #2c3e50;
        }
        .email i{
            position: absolute;
            right: 30px;
            top: 45%;
            transform: translateY(-50%);
            font-size: 20px;
        }
        .new-password i{
            position: absolute;
            right: 30px;
            top: 66%;
            transform: translateY(-50%);
            font-size: 20px;
        }
        .changebtn{
             width: 100%;
             height: 40px;
             background: black;
             border-radius: 40px;
             cursor: pointer;
             color: #ffffff;
             font-weight: 600;
        }
        .footer{
            position: absolute;
            float: bottom;
            margin-bottom: 0%;
            margin-left: 3%;

        }
        #home-button {
            background-color: #555;
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            cursor: pointer;
            position: absolute;
            left: 16px;
            top: 7%;
        }
        #home-button:hover {
            background-color: #777;
        }
    </style>
    <body>
        <div class="header">
            <span class="text1">STUDENT PORTAL</span>
            <br />
            <span class="text2">UYA - Upload Your Assignments</span>
            <button id="home-button" onclick="location.href='main_login.php';">Home</button>
        </div>
        <div class="container">
            <?php if (isset($error_message)): ?>
                <p><?php echo $error_message; ?></p>
            <?php endif; ?>
            <form class="stdlogin" action="" method="POST">
                <h1>Forgot Password</h1>
                <div class="email">
                     <input type="email" name="std_email" id="inputEmail" placeholder="Email" required>
                    <i class='bx bxs-user'></i>
                </div><br>
                <div class="new-password">
                    <input type="password" name="pass" id="password" placeholder="New Password" required>
                    <i class='bx bxs-lock-alt'></i>
                </div><br>
                <button type="submit" class="changebtn" name="change">Change</button>
            </form>
        </div>
        <div class="footer">
            <span>&copy; UYA, All rights reserved.</span>
        </div>
    </body>
</html>
