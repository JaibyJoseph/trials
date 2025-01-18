<?php
// Database configuration
$host = "localhost"; // Your database host
$username = "root"; // Default username for XAMPP MySQL
$password = ""; // Default password for XAMPP MySQL
$database = "student_data"; // Your database name

// Create database connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function insertImage($conn, $table, $id_column, $image_column, $folder_path)
{
    // Get list of image files in folder
    $files = glob($folder_path . '/*.{jpg,jpeg,png,gif}', GLOB_BRACE);

    // Iterate over each image file
    foreach ($files as $file) {
        // Read image file
        $image_data = file_get_contents($file);
        
        // Get filename without extension (assuming filename is student/teacher ID)
        $filename = pathinfo($file, PATHINFO_FILENAME);

        // Check if the image column is already NULL
        $stmt = $conn->prepare("SELECT $image_column FROM $table WHERE $id_column = ?");
        $stmt->bind_param("s", $filename);
        $stmt->execute();
        $stmt->bind_result($existing_image);
        $stmt->fetch();
        $stmt->close();

        if ($existing_image !== null) {
            // Prepare and execute SQL statement to update image data
            $stmt = $conn->prepare("UPDATE $table SET $image_column = ? WHERE $id_column = ?");
            $stmt->bind_param("ss", $image_data, $filename);
            //$stmt->send_long_data(0, $image_data);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                echo "Image updated successfully for $filename.<br>";
            } else {
                echo "Error updating image for $filename. <br>";
            }

            // Close statement
            $stmt->close();
        } else {
            // Prepare and execute SQL statement to insert image data
            $stmt = $conn->prepare("UPDATE $table SET $image_column = ? WHERE $id_column = ?");
            $stmt->bind_param("bs", $image_data, $filename);
            $stmt->send_long_data(0, $image_data);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                echo "Image inserted successfully for $filename.<br>";
            } else {
                echo "Error inserting image for $filename. <br>";
            }

            // Close statement
            $stmt->close();
        }
    }
}

// Update or insert student photos
InsertImage($conn, 'student_data', 'student_id', 'student_picture', 'student_photos/');

// Update or insert teacher photos
InsertImage($conn, 'teachers', 'id', 'teacher_picture', 'teacher_photos/');
?>