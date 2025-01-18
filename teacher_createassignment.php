<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);


// Include database configuration
include('db_config.php');

require 'PHPMailer.php';
require 'SMTP.php';
require 'Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Create a new PHPMailer instance
$mail = new PHPMailer(True);

// Configure SMTP settings
$mail->isSMTP();
$mail->Host = 'smtp.gmail.com';
$mail->SMTPAuth = true;
$mail->Username = 'officialproject232@gmail.com'; //add your email here
$mail->Password = 'gxne sgwe wkes mchd'; //add your app password here
$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
$mail->Port = 587;


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

// Function to send email to students
function sendAssignmentEmailToStudents($con, $branch, $assignment_details, $mail) {
    // Fetch student emails from the same branch as the teacher
    $query = "SELECT std_email FROM student_data WHERE branch = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("s", $branch);
    $stmt->execute();
    $result = $stmt->get_result();

    // Set email parameters
    $mail->setFrom('officialproject232@gmail.com', 'Admin'); //add your email here
    $mail->Subject = "New Assignment Uploaded";
    $mail->Body = "Dear Student,\n\nA new assignment has been uploaded by your teacher.\n\n";
    $mail->Body .= "Assignment Details:\n";
    $mail->Body .= $assignment_details;
    $mail->Body .= "\n\nPlease log in to your account to view or submit the assignment.\n\nRegards,\nUYA Assignment portal";

    // Send email to each student
    while ($row = $result->fetch_assoc()) {
        $to = $row['std_email'];
        $mail->addAddress($to);
        // Send email
        if ($mail->send()) {
            $success = True;
        } else {
            $success = False;
        }
    }

    if(!$success){
        echo "Failed to send notification email to students!";
    }
    else{
        echo "Notification email successfully sent to students!";
    }
}

// Handle file upload if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['upload_assignment'])) {
    // Check if a file was selected and subject is chosen
    if ($_FILES['assignment']['error'] === UPLOAD_ERR_OK) {
        $file_path = "uploads/" . $teacher['branch'] . "/";
        // Create the destination directory if it doesn't exist
        if (!file_exists($file_path)) {
            mkdir($file_path, 0777, true);
        }
        $selected_subject = $_SESSION['selected_subject'];
        $original_file_name = $_FILES["assignment"]["name"];
        $file_extension = pathinfo($original_file_name, PATHINFO_EXTENSION); // Get the file extension
        $new_file_name = $selected_subject . "_assignment." . $file_extension; // Construct new filename with original file extension
        $file_path .= $new_file_name;
        if (move_uploaded_file($_FILES["assignment"]["tmp_name"], $file_path)) {
            // Save deadline to the database
            $selected_subject_id = array_search($selected_subject, $subjects); // Get the subject ID
            $deadline = $_POST['deadline']; // Get the deadline from the form
            $query = "UPDATE subjects SET deadline = '$deadline' WHERE subject_id = $selected_subject_id";
            $con->query($query);
            $upload_result = "<p>File uploaded successfully.</p>";
        } else {
            $upload_result = "<p>Error uploading file.</p>";
        }
        // Send email to students
        $teacher_branch = $teacher['branch']; // Assuming you have the branch information of the teacher
        $assignment_details = "Subject: " . $_SESSION['selected_subject'] . "\n"; // Customize the assignment details as needed
        sendAssignmentEmailToStudents($con, $teacher_branch, $assignment_details, $mail);
    } else {
        $upload_result = "<p>Please select a subject before uploading.</p>";
    }
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
    <title>Create Assignments</title>
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
        select, input[type="submit"], input[type="button"], input[type="file"] {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            background-color: #4CAF50;
            color:white;
            cursor: pointer;
            font-size: 16px;
            margin-bottom: 10px;
            margin-top: 30px;
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
    <h3>Upload Assignments Here</h3>
    <?php if (empty($selected_subject)): ?>
    <form method="post" enctype="multipart/form-data">
        <select name="subject_id" id="subject">
            <option value="">Select Subject</option>
            <?php foreach ($subjects as $subject_id => $subject_name): ?>
                <option value="<?php echo $subject_id; ?>"><?php echo $subject_name; ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit" name="choose_subject">Choose</button>
    </form>
    <?php else: ?>
        <label for="assignment">Selected Subject to Upload Assignment : <?php echo $selected_subject; ?></label>
        <?php if (isset($upload_result)) echo $upload_result; ?>
        <form method="post" enctype="multipart/form-data" action="">
            <label for="assignment">Upload Assignment:</label>
            <input type="file" name="assignment" required>
            </br>
            <label for="deadline">Set Deadline:</label>
            <input type="date" name="deadline" required min="<?php echo date('Y-m-d');?>">
            <button type="submit" name="upload_assignment">Upload</button>
        </form>
        <form method="post">
            <button type="submit" name="return_button">Return</button>
        </form>
    <?php endif; ?>
</body>
</html>