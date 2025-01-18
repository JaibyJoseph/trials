<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer.php';
require 'SMTP.php';
require 'Exception.php';

// Start the session
session_start();

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data and escape it for security
    $std_name = $_POST['std_name'];
    $std_email = $_POST['std_email'];
    $gender = $_POST['gender'];
    $address1 = $_POST['address1'];
    $address2 = $_POST['address2'];
    $std_id = $_POST['std_id'];
    $pass = $_POST['pass'];
    $branch = $_POST['branch'];
    $branch_id = $_POST['branch_id'];
    $phone = $_POST['phone'];
    $std_picture = $_FILES['std_picture'];

    // Check if a profile photo is uploaded
    if(isset($_FILES['fileToUpload'])){
        $file_name = $_FILES['fileToUpload']['name'];
        $file_tmp = $_FILES['fileToUpload']['tmp_name'];

        // Move the uploaded file to the destination directory
        $uploads_dir = 'img/';
        move_uploaded_file($file_tmp, $uploads_dir . $file_name);
    }

    // Establish database connection
    $conn = mysqli_connect("localhost", "root", "", "administrator");

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if student ID or email already exists
    $check_query = "SELECT * FROM student_data WHERE std_id = ? OR std_email = ?";
    $check_stmt = $conn->prepare($check_query);
    $check_stmt->bind_param("ss", $std_id, $std_email);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result->num_rows > 0) {
        // If student ID or email already exists, display error message
        echo "<script>alert('Student ID or Email already exists. Enter the data again'); window.history.back();</script>";
    } else {
        // Prepare and bind the SQL statement
        $insert_query = "INSERT INTO student_data (std_name, std_id, std_email, phone, branch, pass, std_picture, address1, address2, gender, branch_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $insert_stmt = $conn->prepare($insert_query);
        $insert_stmt->bind_param("sssssssssss", $std_name, $std_id, $std_email, $phone, $branch, $pass, $std_picture, $address1, $address2, $gender, $branch_id);

        // Execute the SQL statement
        try {
            if ($insert_stmt->execute()) {
                // Send email
                $mail = new PHPMailer(true);

                //Server settings
                $mail->isSMTP();                                            // Send using SMTP
                $mail->Host       = 'smtp.gmail.com';                    // Set the SMTP server to send through
                $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
                $mail->Username   = 'officialproject232@gmail.com';                     // SMTP username
                $mail->Password   = 'gxne sgwe wkes mchd';                               // SMTP password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
                $mail->Port       = 587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

                //Recipients
                $mail->setFrom('officialproject232@gmail.com', 'Admin');
                $mail->addAddress($std_email);     // Add a recipient

                $subject = 'New Student Registration';
                $message = "
                <html>
                <body>
                    <p>Hello $std_name,</p>
                    <p>You have been successfully registered as a student. You can login on the UYA portal with the credentials mentioned below:</p>
                    <ul>
                        <li><strong>Student ID:</strong> $std_email</li>
                        <li><strong>Password:</strong> $pass</li>
                    </ul>
                    <p>Thankyou.</p>
                    <p>Regards,<br>Admin <br>UYA Assignment Portal</p>
                </body>
                </html>
            ";

                // Content
                $mail->isHTML(true);                                  // Set email format to HTML
                $mail->Subject = $subject;
                $mail->Body    = $message;

                // Attempt to send email
                if ($mail->send()) {
                    echo "<script>alert('New record created and email sent.'); window.location.href = 'managestd.php';</script>";
                } else {
                    echo "<script>alert('Failed to send email. Please contact the administrator.'); window.history.back();</script>";
                }
            }
        } catch (mysqli_sql_exception $exception) {
            if ($exception->getCode() == 1062) { // Error code for duplicate entry
                echo "<script>alert('Student ID already exists. Please choose a different one.'); window.history.back();</script>";
            } else {
                echo "<script>alert('Error: " . $exception->getMessage() . "'); window.history.back();</script>";
            }
        }

        // Close statement and database connection
        $insert_stmt->close();
        $conn->close();
    }
}
?>
