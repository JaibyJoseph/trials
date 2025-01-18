<?php

// Include database configuration
include('db_config.php');


// Initialize selected subject and students array
$selected_subject = "";
$students = array();
$show_table = false;
$show_submit_button = false;
$show_success_message = false;
$show_dropdown = true;

// Fetch teacher data from database
$userid = $_SESSION['userid'];
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
    while ($row = $subjects_result->fetch_assoc()) {
        $subjects[$row['subject_id']] = $row['subject_name'];
    }

    // Handle subject selection if form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_subject'])) {
        $selected_subject_id = $_POST['subject'];
        $selected_subject = $subjects[$selected_subject_id];
        $_SESSION['selected_subject'] = $selected_subject;
        $show_table = true;
        $show_dropdown = false;

        // Fetch students for the selected subject along with their existing grades
        $query = "SELECT * FROM student_data WHERE branch_id = {$teacher['branch_id']}";
        $students_result = $con->query($query);
        while ($row = $students_result->fetch_assoc()) {
            // Fetch existing grade for each student
            $grade_query = "SELECT * FROM student_grades 
                            WHERE std_id = '{$row['std_id']}' AND subject_name = '$selected_subject'";
            $grade_result = $con->query($grade_query);
            if ($grade_result->num_rows > 0) {
                $grade_row = $grade_result->fetch_assoc();
                $row['total_marks'] = $grade_row['total_marks'];
                $row['max_marks'] = $grade_row['max_marks'];
            } else {
                // If no entries exist in the database for this subject, set default values
                $row['total_marks'] = "";
                $row['max_marks'] = "";
            }
            $students[] = $row;
        }
        $show_submit_button = true;
    }
}

// Handle form submission to submit grades
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_grades'])) {
    // Assuming the form sends arrays of student IDs, corresponding grades, and maximum marks
    $student_grades = $_POST['total_marks']; // Assuming the form sends the total marks array
    $max_marks = $_POST['max_marks']; // Assuming the form sends the maximum marks array

    foreach ($student_grades as $std_id => $total_marks) {
        $subject = $_SESSION['selected_subject'];

        // Update existing record if it exists
        $query = "UPDATE student_grades 
                  SET max_marks = '$max_marks[$std_id]', 
                      total_marks = '$total_marks'
                  WHERE std_id = '$std_id' AND subject_name = '$subject'";
        $con->query($query);

        // If no rows were affected by the update, it means the record didn't exist, so insert a new record
        if ($con->affected_rows == 0) {
            $query = "INSERT INTO student_grades (std_id, subject_name, max_marks, total_marks) 
                      VALUES ('$std_id', '$subject', '$max_marks[$std_id]', '$total_marks')";
            $con->query($query);
        }
    }
    $show_table = false;
    $show_submit_button = false;
    $show_success_message = true;
    $show_dropdown = false;
}

// Handle form submission to change marks for individual students
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['change_marks'])) {
    $std_id = $_POST['change_marks'];
    $new_total_marks = $_POST['new_total_marks'][$std_id];
    $subject = $_SESSION['selected_subject'];

    // Update the marks for the selected student
    $query = "UPDATE student_grades 
              SET total_marks = '$new_total_marks' 
              WHERE std_id = '$std_id' AND subject_name = '$subject'";
    $con->query($query);
}



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enter Student Grades</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        h2 {
            text-align: center;
        }
        form {
            margin-bottom: 20px;
        }
        label {
            font-weight: bold;
            margin-bottom: 20px;
        }
        select, input[type="submit"], input[type="button"] {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
            font-size: 16px;
            margin-right: 10px;
        }
        select {
            margin-right: 10px;
        }
        input[type="submit"]:hover, input[type="button"]:hover {
            background-color: #45a049;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        button[type="submit"], button[type="button"] {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
            font-size: 16px;
        }
        button[type="submit"]:hover, button[type="button"]:hover {
            background-color: #45a049;
        }

    </style>
</head>
<body>
    <form method="post">
        <?php if (!empty($subjects)): ?>
            <?php if ($show_dropdown): ?>
                <label for="subject">Select Subject to Enter Grades : </label>
                <select name="subject" id="subject">
                    <option value="">Select Subject</option>
                    <?php foreach ($subjects as $subject_id => $subject_name): ?>
                        <option value="<?php echo $subject_id; ?>"><?php echo $subject_name; ?></option>
                    <?php endforeach; ?>
                </select>
                <input type="submit" value="Continue" name="submit_subject">
            <?php endif; ?>
        <?php endif; ?>
    </form>
    <form method="post">
        <?php if (!empty($students)): ?>
            <?php if ($show_table): ?>
                <label for="subject">Students for the <?php echo $selected_subject; ?> Subject : </label>
                <table>
                    <thead>
                        <tr>
                            <th>Student ID</th>
                            <th>Student Name</th>
                            <th>Total Marks</th>
                            <th>Maxium Marks</th>
                            <th>Change Marks</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($students as $student): ?>
                            <tr>
                                <td><?php echo $student['std_id']; ?></td>
                                <td><?php echo $student['std_name']; ?></td>
                                <td>
                                    <?php if ($student['total_marks'] == ""): ?>
                                        <!-- Display text boxes to enter grades only if no existing entries for this subject -->
                                        <input type="text" name="total_marks[<?php echo $student['std_id']; ?>]" value="">
                                    <?php else: ?>
                                        <?php echo $student['total_marks']; ?>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($student['max_marks'] == ""): ?>
                                        <!-- Display text boxes to enter grades only if no existing entries for this subject -->
                                        <input type="text" name="max_marks[<?php echo $student['std_id']; ?>]" value="">
                                    <?php else: ?>
                                        <?php echo $student['max_marks']; ?>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($student['total_marks'] == "" && $student['max_marks'] == ""): ?>
                                        <?php echo "-"; ?>
                                    <?php else: ?>
                                        <!-- Display a "Change" button to update marks for this student -->
                                        <input type="text" name="new_total_marks[<?php echo $student['std_id']; ?>]" value="">
                                        <input type="hidden" name="student_id" value="<?php echo $student['std_id']; ?>">
                                        <button type="submit" name="change_marks" value="<?php echo $student['std_id']; ?>">Change</button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <button type="submit" name="return_button">Return</button>
                <button type="submit" name="submit_grades">Submit Grades</button>
            <?php endif; ?>
        <?php endif; ?>
    </form>
    <?php if ($show_success_message): ?>
        <form method="post">
            <label for="subject">Grades have been submitted successfully!</label>
            <button type="submit" name="return_button">Return</button>
        </form>
    <?php endif; ?>
</body>
</html>
