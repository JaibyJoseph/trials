<?php


// Include database configuration
include('db_config.php');
session_start();

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve admin details from the form
    $std_email = $_POST["std_email"];
    $pass = $_POST["pass"];

    // Perform authentication
    $query = "SELECT * FROM student_data WHERE std_email='$std_email' AND pass='$pass'";
    $result = $con->query($query);

    // Check if the user is a admin
    if ($result->num_rows > 0) {
        // Fetch user details
        $user = $result->fetch_assoc();

        // Set session variables for admin
        $_SESSION['std_email'] = $user['std_email']; 
        $_SESSION['pass'] = $user['pass'];
        $_SESSION['std_id'] = $user['std_id'];
        $_SESSION['std_name']=$user['std_name'];
        $_SESSION['std_picture']=$user['std_picture'];

        // Redirect to student home page
        header("Location: student_home.php");
        exit();
    } else {
            // Invalid username or password
            $error_message = "Invalid username or password. Please try again.";
    }
}

//redirect user to main page
if (isset($_GET['homepg'])) {
    // Destroy the session
    session_destroy();
    // Redirect to main page
    header("Location: MainPage.php");
    exit();
}

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
        #header {
            background-color: #333;
            color: #fff;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            text-align: center;
        }
        #header h1 {
            margin: 5px 0;
        }
        #header h2 {
            margin: 5px 0;
        }
        #homepg {
            background-color: #555;
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            cursor: pointer;
            position: absolute;
            right: 20px;
            top: 10%;
        }
        #homepg:hover {
            background-color: #777;
        }
        .container{
            display: flex;
            justify-content: center;
            text-align: center;
            min-height: 85vh;
            position: relative;
            margin-top: auto;
            background-color: #34495e;
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
        .container .password{
            width: 100%;
            height: 40px;
        }
        .stdlogin input{
            text-align: center;
            width: 100%;
            height: 100%;
            background: transparent;
            border-style: solid;
            border-color: white;
            border-radius: 40px;
            font-size: 16px;
            font-weight: 500;
        }
        .stdlogin input::placeholder{
            color: black;
        }
        .email i{
            position: absolute;
            right: 30px;
            top: 45%;
            transform: translateY(-160%);
            font-size: 20px;
        }
        .password i{
            position: absolute;
            right: 30px;
            top: 65%;
            transform: translateY(-200%);
            font-size: 20px;
        }
        .loginbtn{
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
        a.forgot-password-link:visited {
            color: #fff; 
        }
    </style>
    <body>
        <div id="header">
            <h1>STUDENT PORTAL</h1>
            <h2>UYA-Upload Your Assignments</h2>
            <button id="homepg" onclick="location.href='?homepg=true';">Home</button>
        </div>
        <div class="container">
            <?php if (isset($error_message)): ?>
                <p><?php echo $error_message; ?></p>
            <?php endif; ?>
            <form class="stdlogin" action="" method="POST">
                <h1>Login</h1>
                <div class="email">
                    <input type="text" id="admin_email" name="std_email" placeholder="User ID" required>
                    <i class='bx bxs-user'></i>
                </div><br>
                <div class="password">
                    <input type="password" id="password" name="pass" placeholder="Password" required>
                    <i class='bx bxs-lock-alt'></i>
                </div><br>
                <button type="submit" value="Login" name="loginbtn" class="loginbtn">Login</button>
                <p><a href="stu-change_password.php" class="forgot-password-link">Forgot Password?</a></p>
            </form>
        </div>
        <div class="footer">
            <span>&copy; UYA, All rights reserved.</span>
        </div>
    </body>
</html>