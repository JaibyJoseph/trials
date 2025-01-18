<?php
include("connection.php");

if(!isset($_SESSION['adminid'])){
    header("Location:ADlogin.php");
    die();
}

// Fetch admin data from database
$query = "SELECT * FROM adminlogin";
$result = $con->query($query);
if ($result->num_rows > 0) {
    $admin = $result->fetch_assoc();
}

//toggle mode
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mode_toggle'])) {
    if (isset($_POST['mode'])) {
        $_SESSION['mode'] = $_POST['mode'];
    }
}

//toggle colour of table
$color_scheme = isset($_SESSION['color_scheme']) ? $_SESSION['color_scheme'] : 'blue'; // Default color scheme
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['apply'])) {
    if (isset($_POST['table_color'])) {
        $color_scheme = $_POST['table_color'];
        $_SESSION['color_scheme'] = $color_scheme;
    }
}

// Default section to display
$section = isset($_GET['section']) ? $_GET['section'] : 'home';

//logout
if (isset($_GET['logout'])) {
    // Destroy the session
    session_destroy();
    // Redirect to login page
    header("Location: ADlogin.php");
    exit();
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
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width-device-width", initial-scale="1">
        <title>ADMIN HOMEPAGE</title>
        <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    </head>
    <style>
        #header {
            width: 100%;
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
        #logout-button {
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
        #logout-button:hover {
            background-color: #777;
        }
        #homepg {
            background-color: #555;
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            cursor: pointer;
            position: absolute;
            right: 110px;
            top: 10%;
        }
        #homepg:hover {
            background-color: #777;
        }

        .footer{
            position: fixed;
            float: bottom;
            bottom: 0;
            margin-left: 3%;
            color: #FAF9F6;
        }
        body{
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }
        #container {
            background-color: <?php echo isset($_SESSION['mode']) && $_SESSION['mode'] === 'dark' ? '#222' : '#f4f4f4'; ?>;
        }
        #sidebar{
            width: 250px;
            background-color: #333;
            color: #fff;
            position: absolute;
            top: 0px;
            left: 0px; 
            height: 100%;
            padding-top: 120px;
        }
        #details{
            text-align: center;
            border: solid;
            padding: 5px;
            margin: 5px;
        }
        .adminpic img{
            width: 220px;
            height: 250px;
        }
        .sidebar-option {
            text-align: center;
            width: 200px;
            padding: 15px 10px;
            border-bottom: 1px solid #555;
        }
        #sidebar ul {
            list-style-type: none;
            padding: 0;
            margin: 10px 0 0 0;
        }
        #sidebar ul li {
            margin-bottom: 10px;
        }
        #sidebar ul li a {
            display: block;
            padding: 10px;
            text-decoration: none;
            color: #fff;
            background-color: #555;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        #sidebar ul li a.active {
            background-color: #000;
        }
        #main-content {
            position: relative;
            margin-left: 250px;
            padding: 20px;
            height: 100vh;
        }
        #profile {
            background-color: #fff;
            border-radius: 5px;
            padding: 20px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            color: <?php echo isset($_SESSION['mode']) && $_SESSION['mode'] === 'dark' ? '#fff' : '#fff'; ?>;
        }
        #profile img {
            width: 300px;
            height: 300px;
            border-radius: 50%;
            margin-right: 20px;
        }
        #profile-info {
            display: flex;
            flex-direction: column;
            height: 200px;
            color: <?php echo isset($_SESSION['mode']) && $_SESSION['mode'] === 'dark' ? '#fff' : '#fff'; ?>;
        }
        #profile-info p {
            margin: 5px;
            width: 200px;
            padding: 10px;
            border-radius: 5px;
            background: #007958;
            color: <?php echo isset($_SESSION['mode']) && $_SESSION['mode'] === 'dark' ? '#fff' : '#fff'; ?>;
        }
        #home{
            display: flex;
            flex-direction: row;
            color: <?php echo isset($_SESSION['mode']) && $_SESSION['mode'] === 'dark' ? '#fff' : '#fff'; ?>;
        }
        #home ul {
            list-style-type: none;
        }
        #home ul li a{
            text-decoration: none;
        }
        .sub-window {
            display: none;
            border: 1px solid #ccc;
            padding: 20px;
            margin-top: 20px;
            background-color: #fff;
            border-radius: 5px;
            color: <?php echo isset($_SESSION['mode']) && $_SESSION['mode'] === 'dark' ? '#000' : '#000'; ?>;
        }
        .user{
            margin-left: 100px;
            font-size: 30px;
            border: solid;
            color: #000;
            border-radius: 20px;
            text-align: center;
            padding-top: 60px;
            height: 120px;
            width: 200px;
            background-color: #d3d3d3;
        }
        .tt{
            position: absolute;
            top: 120px;
            right: 300px; 
            height: 50px;
            width: 400px;
            padding: 10px;
            border: solid;
            border-radius: 10px;
            background-color: #d3d3d3;
            color: black;
            margin: 10px;
            padding-top: 20px;
            text-align: center;
            font-weight: bold;
        }
        .number{
            color: red;
        }
        .ts{
            position: absolute;
            top: 320px;
            right: 300px; 
            height: 50px;
            width: 400px;
            padding: 10px;
            border: solid;
            border-radius: 10px;
            background-color: #d3d3d3;
            color: black;
            margin: 10px;
            padding-top: 20px;
            text-align: center;
            font-weight: bold;
        }
        .back_btn{
            position: absolute;
            width: 30px;
            margin-top: 2px;
            left: 5px;
            border-radius: 5px; 
        }
        .next_btn{
            position: absolute;
            width: 30px;
            margin-top: 2px;
            margin-left: 5px;
            left: 35px;
            border-radius: 5px;
        }
    </style>
    <body>
        <div id="header">
            <h1>ADMIN PORTAL</h1>
            <h2>UYA-Upload Your Assignments</h2>
            <button id="logout-button" onclick="location.href='?logout=true';">Logout</button>
            <button id="homepg" onclick="location.href='?homepg=true';">Home</button>
        </div>
        <div id="container">
            <div id="sidebar">
                <div id="details">
                    <span class="adminpic">
                        <?php
                        // Fetch the image data from the database based on the student ID
                        $adminid = $_SESSION['adminid'];
                        $query = "SELECT admphoto FROM adminlogin WHERE adminid = '$adminid'";
                        $result = $con->query($query);

                        if ($result && $result->num_rows > 0) {
                            $row = $result->fetch_assoc();
                            $image_data = $row['admphoto'];

                            // Output the image data as a base64-encoded image
                            $image_base64 = base64_encode($image_data);
                            $image_src = 'data:image/jpeg;base64,' . $image_base64;

                            // Display the image
                            echo "<img src=\"$image_src\">";
                        } 
                        ?>
                    </span>
                    <br/></br>
                    <span class="name">Admin Name : <?php echo $_SESSION['adminname'] ?></span>
                    <br/>
                    <span class="id">Admin ID : <?php echo $_SESSION['adminid'] ?></span>
                </div>
                <br/>
                <ul>
                    <li class="sidebar-option"><a href="?section=home">Home</a></li>
                    <li class="sidebar-option"><a href="?section=profile">Profile</a></li>
                    <li class="sidebar-option"><a href="?section=settings">Settings</a></li>
                </ul>
            </div>
            
            <div id="main-content">

            <header>
                <!-- Forward and Backward Buttons -->
                <button class="back_btn" onclick="goBack()"><i class='bx bxs-left-arrow-alt'></i></button>
                <button class="next_btn" onclick="goForward()"><i class='bx bxs-right-arrow-alt'></i></button>
            </header>
            <script>
                function goBack() {
                    window.history.back();
                }
                function goForward() {
                    window.history.forward();
                }
            </script></br></br>

                <?php if ($section === 'home'): ?>
                    <div id="home" style="background-color: <?php echo isset($_SESSION['mode']) && $_SESSION['mode'] === 'dark' ? '#333' : '#fff';?>">
                        <div id="dasboard">
                            <ul>
                                <li class="user"><a href="manageteach.php"><b>Manage </br> Teachers</b></a></li></br>
                                <li class="user"><a href="managestd.php"><b>Manage </br> Students</b></a></li>
                            </ul>
                            </br></br>
                            <div class="tt"> Total Teachers
                                <?php
                                    $dash_category_query = "SELECT * FROM teacher";
                                    $dash_category_query_run = mysqli_query($con, $dash_category_query);
                                    
                                    if($category_total = mysqli_num_rows($dash_category_query_run))
                                    {
                                        echo '<h3 class="number"> '.$category_total.' </h3>';
                                    }
                                    else{
                                        echo '<h3 class="number">No Data</h3>';
                                    }
                                ?>   
                            </div>
                            <div class="ts"> Total Students
                                <?php
                                    $dash_category_query = "SELECT * FROM student_data";
                                    $dash_category_query_run = mysqli_query($con, $dash_category_query);
                                    
                                    if($category_total = mysqli_num_rows($dash_category_query_run))
                                    {
                                        echo '<h3 class="number"> '.$category_total.' </h3>';
                                    }
                                    else{
                                        echo '<h3 class="number">No Data</h3>';
                                    }
                                ?>
                            </div>
                        </div>
                        </br></br>
                    </div>
                
                <?php elseif ($section === 'profile'): ?>
                    <div id="profile" style="background-color: <?php echo isset($_SESSION['mode']) && $_SESSION['mode'] === 'dark' ? '#333' : '#fff';?>">
                        <?php
                            // Fetch the image data from the database based on the student ID
                            $adminid = $_SESSION['adminid'];
                            $query = "SELECT admphoto FROM adminlogin WHERE adminid = '$adminid'";
                            $result = $con->query($query);

                            if ($result && $result->num_rows > 0) {
                                $row = $result->fetch_assoc();
                                $image_data = $row['admphoto'];

                                // Output the image data as a base64-encoded image
                                $image_base64 = base64_encode($image_data);
                                $image_src = 'data:image/jpeg;base64,' . $image_base64;

                                // Display the image
                                echo "<img src=\"$image_src\">";
                            } 
                            ?>
                        <div id="profile-info">
                            <p><strong>Name : </strong><?php echo $_SESSION['adminname']; ?></p>
                            <p><strong>Admin ID : </strong><?php echo $_SESSION['adminid']; ?></p>
                            <p><strong>Admin Email : </strong><?php echo $_SESSION['admin_email']; ?></p>
                        </div>
                    </div>

                <?php elseif ($section === 'settings'): ?>
                    <div class="sub-window" style="display: block; background-color: <?php echo isset($_SESSION['mode']) && $_SESSION['mode'] === 'dark' ? '#333' : '#fff'; ?>">
                            <form method="post" action="?section=settings">
                                <input type="hidden" name="mode_toggle" value="true">
                                <label> Choose Theme: </label> 
                                <button type="submit" name="mode" value="light">Light Mode</button>
                                <button type="submit" name="mode" value="dark">Dark Mode</button> <br><br>
                            </form>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="footer">
            <span>&copy; UYA, All rights reserved.</span>
        </div>
    </body>
</html>
