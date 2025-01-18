

<?php

// Include database configuration
include('db_config.php');


// Get user ID
$std_id = $_SESSION['std_id'];
// Fetch student data from database
$query = "SELECT * FROM student_data WHERE std_id = '$std_id'";
$result = $con->query($query);
if ($result->num_rows > 0) {
    $student = $result->fetch_assoc();
}
// Fetch assignments for the student's branch
$query = "SELECT * FROM subjects WHERE branch_id = '{$student['branch_id']}'";
$assignments_result = $con->query($query);


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Home</title>
</head>
<body>
    <div>
            <?php if ($assignments_result->num_rows > 0): ?>
                <table class="<?php echo $color_scheme; ?>">
                    <thead>
                        <tr>
                            <th>Subject</th>
                            <th>View Assignment</th>
                            <th>Deadline</th>
                            <th>Submit Assignment</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $assignments_result->fetch_assoc()): ?>
                            <tr>
                                <?php $deadline = $row['deadline']; ?>
                                <td><?php echo $row['subject_name']; ?></td>
                                <td>
                                    <?php
                                    $file_path1 = "uploads/" . $student['branch'] . "/" . $row['subject_name'] . "_assignment.*";
                                    $assignment_files = glob($file_path1);
                                    if (!empty($assignment_files) && $deadline && strtotime($deadline) > time()) {
                                        echo '<a href="' . $assignment_files[0] . '" target="_blank" style="color: inherit;">View Assignment</a>';
                                    } else {
                                        echo '-';
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php 
                                        $deadline = $row['deadline'];
                                        if(($deadline && strtotime($deadline) < time()) || empty($assignment_files)) {
                                            echo '-'; // Deadline has passed
                                        } else {
                                            echo $deadline ? $deadline : $deadline;
                                        }
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    if (!empty($assignment_files) && $deadline && strtotime($deadline) > time()) {
                                        ?>
                                        <form method="post" enctype="multipart/form-data">
                                            <input type="hidden" name="subject" value="<?php echo $row['subject_name']; ?>">
                                            <input type="file" name="assignment" required>
                                            <button type="submit" name="submit_<?php echo $row['subject_id']; ?>">Submit</button>
                                        </form>
                                        <?php
                                        if (isset($_POST['submit_' . $row['subject_id']])) {
                                            $file_path2 = "submissions/" . $student['std_id'] . "/";
                                            // Create the destination directory if it doesn't exist
                                            if (!file_exists($file_path2)) {
                                                mkdir($file_path2, 0777, true);
                                            }
                                            $uploaded_file_name = $_FILES["assignment"]["name"];
                                            $file_extension = pathinfo($uploaded_file_name, PATHINFO_EXTENSION); // Get the file extension
                                            $file_path2 .= $row['subject_name'] . "_assignment." . $file_extension;
                                            if (move_uploaded_file($_FILES["assignment"]["tmp_name"], $file_path2)) {
                                                echo "<p>File uploaded successfully.</p>";
                                            } else {
                                                echo "<p>Error uploading file.</p>";
                                            }
                                        }
                                    } else {
                                        echo '-';
                                    }
                                    ?>
                                </td>

                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No assignments available.</p>
            <?php endif; ?>
    </div>
</body>
</html>
