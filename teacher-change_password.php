<?php
session_start();
include('db_config.php');
if(isset($_POST['change'])) { // upon clicking the change button in stu-cp
    $email = $_POST['email']; //email from rmail i/p field in stu-change_password.php
    $new_password = $_POST['new_password']; //new password from stu-change_password file,pw input field

    $changeQuery = $conn->query("UPDATE teachers SET password = '$new_password' WHERE teacher_email = '$email'");

    if($changeQuery) {
        header("Location: success.html");
    }
    else {
        header("Location: teacher_login.php");
        exit();}
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width-device-width", initial-scale="1">
        <title>Teacher Login</title>
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
            <span class="text1">TEACHER PORTAL</span>
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
                     <input type="email" name="email" id="inputEmail" placeholder="Email" required>
                    <i class='bx bxs-user'></i>
                </div><br>
                <div class="new-password">
                    <input type="password" name="new_password" id="password" placeholder="New Password" required>
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
