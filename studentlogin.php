<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "connection.php";
session_start();

if(isset($_POST['loginbtn'])){

   $student_id = mysqli_real_escape_string($conn, $_POST['student_id']);
   $pass = mysqli_real_escape_string($conn, ($_POST['password']));

   $select = mysqli_query($conn, "SELECT * FROM `student` WHERE student_id = '$student_id' AND password = '$pass'") or die(mysqli_error($conn));

   if(mysqli_num_rows($select) > 0){
      $row = mysqli_fetch_assoc($select);
      $_SESSION['student_id'] = $row['student_id'];
      $_SESSION['email'] = $row['email']; // Add email to session

      // Add debug statement
      echo "Login successful. Redirecting..."; 
      header('location: student-portal.php');
      exit; // Ensure no further output is sent
   } else {
      $message = 'Incorrect password or username!';
      // Add debug statement
      echo $message;
   }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>
    student Login
  </title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script> 
<style>
section
{
    margin-top: -20px;
}
body{
  background-image: url("background.jpg");
}
/* CSS for login form */
.stdlogin {
    width: 400px;
    height: 300px;
    padding: 20px;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
}

.stdlogin h1 {
    font-size: 24px;
    color: #333;
    text-align: center;
    margin-bottom: 20px;
}

.stdlogin input[type="text"],
.stdlogin input[type="password"] {
    width: calc(100% - 40px);
    padding: 10px;
    margin-bottom: 20px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

.stdlogin input[type="submit"] {
    width: 100%;
    padding: 10px;
    background-color: #007bff;
    border: none;
    border-radius: 5px;
    color: #fff;
    font-size: 16px;
    cursor: pointer;
}

.stdlogin input[type="submit"]:hover {
    background-color: #0056b3;
}
.login input[type="submit"] {
    padding: 12px 24px; /* Increase padding to increase button size */
    background-color: #007bff;
    border: none;
    border-radius: 5px;
    color: #fff;
    font-size: 18px; /* Increase font size */
    cursor: pointer;
    width: 200px; /* Increase width to increase button size */
    box-sizing: border-box; /* Include padding in width calculation */
}

.login input[type="submit"]:hover {
    background-color: #0056b3;
}

</style>
</head>
<body>

    <nav class="navbar navbar-inverse">
        <div class="container-fluid">
            <div class="navbar-header">
       <img src="logo.png" width="60">
        <h1 style="color:white; font-size: 20px;">INFRASTRUCTURE MANAGEMENT</h1>
         </div>
         <br><br>
            <ul class="nav navbar-nav navbar-right">
                <li><a href="/project/admin/index.php"><span class="glyphicon glyphicon-home"> HOME</span></a></li>
            </ul>
        </div>
     </nav>

<section>
    <br><br><br>
    <div class="stdlogin">
    <h1>Login</h1>
    <form action="" method="post">
         <input type="text" name="student_id" id="student_id" placeholder="User ID" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" name="loginbtn" style="padding: 5px 15px; font-size: 16px; width: 150px; margin-left:95px;background-color:lightgreen">Login</button>
    </form>
    <p><a href="stu-forgot_password.html">Forgot Password?</a></p>
</div>



</body>
</html>
