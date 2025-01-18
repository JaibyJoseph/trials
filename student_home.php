<?php
// session started

//logout
if (isset($_GET['logout'])) {
    // Destroy the session
    session_destroy();
    // Redirect to login page
    header("Location: login.php");
    exit();
}


// Include database configuration
include('db_config.php');
session_start();

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

// Get user ID
$std_id = $_SESSION['std_id'];

// Fetch student data from database
$query = "SELECT * FROM student_data WHERE std_id = '$std_id'";
$result = $con->query($query);
if ($result->num_rows > 0) {
    $student = $result->fetch_assoc();
}

// Fetch assignments for the student's branch
$query = "SELECT * FROM subjects WHERE branch = '{$student['branch']}'";
$assignments_result = $con->query($query);

// Fetch grades for the student
$query = "SELECT s.subject_name, g.total_marks, g.max_marks
          FROM subjects s 
          LEFT JOIN student_grades g ON s.subject_name = g.subject_name 
          WHERE g.std_id = '$std_id'";
$grades_subject = $con->query($query);

// Default section to display
$section = isset($_GET['section']) ? $_GET['section'] : 'profile';

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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Assignment Homepage</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        overflow-x: hidden;
    }
    #container {
        background-color: <?php echo isset($_SESSION['mode']) && $_SESSION['mode'] === 'dark' ? '#222' : '#f4f4f4'; ?>;
        color: <?php echo isset($_SESSION['mode']) && $_SESSION['mode'] === 'dark' ? '#fff' : '#000'; ?>;
        min-height: 100vh;
    }
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
    #sidebar {
        width: 250px;
        background-color: #333;
        color: #fff;
        position: fixed;
        top: 14%;
        left: 0;
        height: 100%;
    }
    .sidebar-option {
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
        background-color: #777;
    }
    #main-content {
        margin-left: 250px;
        padding: 20px;
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
    h1 {
        margin-top: 0;
    }
    table {
        width: 100%;
        border-collapse: collapse;
    }

    th, td {
        padding: 8px;
        text-align: center;
        border-bottom: 1px solid #ddd;
    }

    /* Blue color scheme */
    table.blue th {
        background: linear-gradient(to right, #0099FF, #0033CC);
        color: white;
    }
    table.blue tr:nth-child(even) {
        background-color: #cce0ff;
    }
    table.blue tr:nth-child(odd) {
        background-color: #b3d9ff;
    }

    /* Red color scheme */
    table.red th {
        background: linear-gradient(to right, #FF3300, #CC0000);
        color: white;
    }
    table.red tr:nth-child(even) {
        background-color: #FFCCCC;
    }
    table.red tr:nth-child(odd) {
        background-color: #FF9999;
    }

    /* Green color scheme */
    table.green th {
        background: linear-gradient(to right, #00CC00, #006600);
        color: white;
    }
    table.green tr:nth-child(even) {
        background-color: #CCFFCC;
    }
    table.green tr:nth-child(odd) {
        background-color: #99FF99;
    }

    /* Orange color scheme */
    table.orange th {
        background: linear-gradient(to right, #FF9900, #FF6600);
        color: white;
    }
    table.orange tr:nth-child(even) {
        background-color: #FFE5CC;
    }
    table.orange tr:nth-child(odd) {
        background-color: #FFCC99;
    }

    /* Purple color scheme */
    table.purple th {
        background: linear-gradient(to right, #9933FF, #6600CC);
        color: white;
    }
    table.purple tr:nth-child(even) {
        background-color: #E6E6FA;
    }
    table.purple tr:nth-child(odd) {
        background-color: #D8BFD8;
    }

    /* Pink color scheme */
    table.pink th {
        background: linear-gradient(to right, #FF66CC, #FF3399);
        color: white;
    }
    table.pink tr:nth-child(even) {
        background-color: #FFC0CB;
    }
    table.pink tr:nth-child(odd) {
        background-color: #FFB6C1;
    }
    
     /* Hover */
    table.blue tr:hover {
        background-color: #66a3ff; 
    }

    table.red tr:hover {
        background-color: #ff8080; 
    }

    table.green tr:hover {
        background-color: #b3ff66; 
    }

    table.orange tr:hover {
        background-color: #ffcc66; 
    }

    table.purple tr:hover {
        background-color: #c0c0c0; 
    }

    table.pink tr:hover {
        background-color: #ffb3b3;
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
        justify-content: space-between;
        height: 200px;
        color: <?php echo isset($_SESSION['mode']) && $_SESSION['mode'] === 'dark' ? '#fff' : '#fff'; ?>;
    }
    #profile-info p {
        margin: 0;
        padding: 10px;
        border-radius: 5px;
        background: linear-gradient(to right, <?php echo $color_scheme === 'blue' ? '#0099FF, #0033CC' : ($color_scheme === 'red' ? '#FF3300, #CC0000' : ($color_scheme === 'green' ? '#00CC00, #006600' : ($color_scheme === 'orange' ? '#FF9900, #FF6600' : ($color_scheme === 'yellow' ? '#FFFF00, #FFCC00' : ($color_scheme === 'purple' ? '#9933FF, #6600CC' : ($color_scheme === 'pink' ? '#FF66CC, #FF3399' : '#0099FF, #0033CC')))))) ?>);
        color: <?php echo isset($_SESSION['mode']) && $_SESSION['mode'] === 'dark' ? '#fff' : '#fff'; ?>;
    }
    label {
    color: <?php echo isset($_SESSION['mode']) && $_SESSION['mode'] === 'dark' ? '#fff' : '#000'; ?>;
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
    </style>
</head>
<body>
    <div id="container">
        <div id="header">
            <h2>Student Portal</h2>
            <h1>UYA-Upload Your Assignments</h1>
            <button id="logout-button" onclick="location.href='?logout=true';">Logout</button>
            <button id="homepg" onclick="location.href='?homepg=true';">Home</button>
        </div>
    
        <div id="sidebar">
            <ul>
                <li class="sidebar-option"><a href="?section=profile">Profile</a></li>
                <li class="sidebar-option"><a href="?section=assignments">Assignments</a></li>
                <li class="sidebar-option"><a href="?section=grades">Grades</a></li>
                <li class="sidebar-option"><a href="?section=settings">Settings</a></li>
            </ul>
        </div>

        <div id="main-content">
            <?php if ($section === 'profile'): ?>
                <div id="profile" style="background-color: <?php echo isset($_SESSION['mode']) && $_SESSION['mode'] === 'dark' ? '#333' : '#fff';?>">
                    <?php
                    // Fetch the image data from the database based on the student ID
                    $std_id = $student['std_id'];
                    $query = "SELECT std_picture FROM student_data WHERE std_id = '$std_id'";
                    $result = $con->query($query);

                    if ($result && $result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        $image_data = $row['std_picture'];

                        // Output the image data as a base64-encoded image
                        $image_base64 = base64_encode($image_data);
                        $image_src = 'data:image/jpeg;base64,' . $image_base64;

                        // Display the image
                        echo "<img src=\"$image_src\">";
                    } 
                    ?>
                    <div id="profile-info">
                        <p><strong>Name : </strong><?php echo $student['std_name']; ?></p>
                        <p><strong>Student ID : </strong><?php echo $student['std_id']; ?></p>
                        <p><strong>Branch : </strong><?php echo $student['branch']; ?></p>
                        <p><strong>Branch ID : </strong><?php echo $student['branch_id']; ?></p>
                    </div>
                </div>
            <?php elseif ($section === 'assignments'): ?>
                <h3>Branch: <?php echo $student['branch']; ?></h3>
                <div class="sub-window" style="display: block; background-color: <?php echo isset($_SESSION['mode']) && $_SESSION['mode'] === 'dark' ? '#333' : '#fff'; ?>">
                    <?php include('student_assignments.php'); ?>
                </div>
            <?php elseif ($section === 'grades'): ?>
                <h3>Branch: <?php echo $student['branch']; ?></h3>
                <div class="sub-window" style="display: block; background-color: <?php echo isset($_SESSION['mode']) && $_SESSION['mode'] === 'dark' ? '#333' : '#fff'; ?>">
                    <table class="<?php echo $color_scheme; ?>">
                        <thead>
                            <tr>
                                <th>Subject</th>
                                <th>Total Marks</th>
                                <th>Maxium Marks</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row2 = $grades_subject->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $row2['subject_name']; ?></td>
                                    <td><?php echo isset($row2['total_marks']) ? $row2['total_marks'] : "No marks available"; ?></td>
                                    <td><?php echo isset($row2['max_marks']) ? $row2['max_marks'] : "No marks available"; ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php elseif ($section === 'settings'): ?>
                <div class="sub-window" style="display: block; background-color: <?php echo isset($_SESSION['mode']) && $_SESSION['mode'] === 'dark' ? '#333' : '#fff'; ?>">
                    <form method="post" action="?section=settings">
                        <input type="hidden" name="mode_toggle" value="true">
                        <label> Choose Theme: </label> 
                        <button type="submit" name="mode" value="light">Light Mode</button>
                        <button type="submit" name="mode" value="dark">Dark Mode</button> <br><br>
                        <label> Change the table colour to: </label>
                        <select name="table_color">
                            <option value="blue">Blue</option>
                            <option value="red">Red</option>
                            <option value="green">Green</option>
                            <option value="orange">Orange</option>
                            <option value="purple">Purple</option>
                            <option value="pink">Pink</option>
                        </select>
                        <button type="submit" name="apply">Apply</button>
                    </form>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>