<?php


// Include database configuration
include('db_config.php');


// Get user ID
$userid = $_SESSION['userid'];

// Initialize selected subject
$selected_subject = "";

// Fetch teacher data from database
$query = "SELECT * FROM teacher WHERE userid = '$userid'";
$result = $con->query($query);
if ($result->num_rows > 0) {
    $teacher = $result->fetch_assoc();
    // Fetch subjects related to the teacher's branch
    $query = "SELECT s.subject_id, s.subject_name
              FROM subjects s
              INNER JOIN branches b ON s.branch_id = b.branch_id
              WHERE b.branch_id = {$teacher['branch_id']}";
    $subjects_result = $con->query($query);
    $subjects = array();
    while ($row = $subjects_result->fetch_assoc()) {
        $subjects[$row['subject_id']] = $row['subject_name'];
    }

    // Handle subject selection if form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['choose_subject'])) {
        $selected_subject_id = $_POST['subject_id'];
        $selected_subject = $subjects[$selected_subject_id];
        $_SESSION['selected_subject'] = $selected_subject;
    }
}

// Fetch students from the teacher's branch
if (!empty($selected_subject)) {
    $branch_id = $teacher['branch_id'];
    $query = "SELECT * FROM student_data WHERE branch_id = $branch_id";
    $students_result = $con->query($query);
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



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Student Assignments</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        h2 {
            text-align: center;
        }
        h3 {
            color: <?php echo $_SESSION['mode'] === 'dark' ? '#ffffff' : '#000000'; ?>;
        }
        form {
            margin-bottom: 20px;
        }
        label {
            font-weight: bold;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        select, input[type="submit"], input[type="button"], input[type="file"] {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
            font-size: 16px;
            margin-bottom: 10px;
            margin-top: 30px;
        }
        select {
            margin-right: 10px;
        }
        input[type="submit"]:hover, input[type="button"]:hover {
            background-color: <?php echo $_SESSION['mode'] === 'dark' ? '#45a049' : '#45a049'; ?>;
        }
        button[type="submit"], button[type="button"] {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
            font-size: 16px;
            margin-bottom: 10px; 
            margin-top: 30px;
        }
        button[type="submit"]:hover, button[type="button"]:hover {
            background-color: <?php echo $_SESSION['mode'] === 'dark' ? '#45a049' : '#45a049'; ?>;
        }
        p{
            color: <?php echo $_SESSION['mode'] === 'dark' ? '#ffffff' : '#000000'; ?>;
        }
    </style>
</head>
<body>
    <h3>View Student's Assignments</h3>
    <?php if (empty($selected_subject)): ?>
    <form method="post">
        <select name="subject_id" id="subject">
            <option value="">Select Subject</option>
            <?php foreach ($subjects as $subject_id => $subject_name): ?>
                <option value="<?php echo $subject_id; ?>"><?php echo $subject_name; ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit" name="choose_subject">Choose</button>
    </form>
    <?php else: ?>
        <h3><?php echo $selected_subject; ?> - Student Assignments</h3>
        <table class="<?php echo $color_scheme; ?>">
            <thead>
                <tr>
                    <th>Student ID</th>
                    <th>Student Name</th>
                    <th>Assignment</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($student = $students_result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $student['std_id']; ?></td>
                    <td><?php echo $student['std_name']; ?></td>
                    <td>
                        <?php
                        $student_folder = "submissions/" . $student['std_id'];
                        $assignment_file = $student_folder . "/" . $selected_subject . "_assignment.*";
                        $assignment_files = glob($assignment_file);
                        if (!empty($assignment_files)) {
                            echo '<a href="' . $assignment_files[0] . '" target="_blank" style="color: inherit;">View Assignment</a>';}
                        else {
                            echo 'Did not upload';
                        }
                        ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <form method="post">
            <button type="submit" name="return_button">Return</button>
        </form>
    <?php endif; ?>
</body>
</html>
