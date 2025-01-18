<?php
include("connection.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Main Page</title>
    <style>
        body {
            position: relative;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            overflow-x: hidden; 
            background: url(field.jpeg);
        }
        .mainpage-container {
            position: absolute;
            width: 100%;
            height: 100vh;
            animation-name: fadeIn;
            animation-duration: 1s;
        }
        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }
        .logo{
            position: absolute;
            width: 500px;
            height: 500px;
            top: 50%;
            left: 50%;
            transform: translate(-100%, -60%);
        }
        .mainpage-links {
            position: absolute; /* Position the links absolutely */
            top: 50%; /* Center vertically */
            left: 50%; /* Center horizontally */
            transform: translate(70%, -70%); /* Center both horizontally and vertically */
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }
        .mainpage-navlink {
            margin: 10px; /* Adjust spacing between links */
            text-decoration: none;
            color: black;
            padding: 20px 40px; /* Increase padding for bigger buttons */
            border: 2px solid var(--dl-color-gray-black); /* Increased border thickness */
            border-radius: 30px; /* Increased border radius */
            background-color: rgba(50, 169, 76, 0.67);
            font-size: 24px; /* Increased font size */
            transition: background-color 0.3s ease;
        }
        .mainpage-navlink:hover {
            background-color: rgba(85, 103, 73, 0.9); /* Darken on hover */
        }
        .home-faq{
            transform: translate(0%, 120%);
            padding: 40px;
            position: absolute;
            border: solid;
            background-color: black;
            color: #E6EBE0;
        }
        .home-container29{
            float: left;
            width: 30%;
        }
        .home-container30{
            float: right;
            width: 60%;
        }
        .footer{
            position: absolute;
            color: black;
            float: bottom;
        }
    </style>
</head>
<body>
    <div class="mainpage-container">
        
        <img src="pictures/download.png" alt="image" class="logo">
        <div class="mainpage-links">
            <a href="login.php" class="mainpage-navlink">STUDENT</a>
            <a href="teacher_login.php" class="mainpage-navlink">TEACHER</a>
            <a href="ADlogin.php" class="mainpage-navlink">ADMIN</a>
        </div>
            <div class="home-faq">
                <div class="faqContainer">
                    <div class="home-faq1">
                    <div class="home-container29">
                        <span class="overline">
                        <span>FAQ</span>
                        <br />
                        </span>
                        <h2 class="home-text85 heading2">Common questions</h2>
                        <span class="home-text86 bodyLarge">
                        <span>
                            Here are some of the most common questions that we get.
                        </span>
                        <br />
                        </span>
                    </div>
                    <div class="home-container30">
                        <div class="question1-container">
                        <span class="question1-text heading3">
                            <span><b>How do I create an assignment as a teacher?</b></span>
                        </span><br>
                        <span class="bodySmall">
                            <span>
                            To create an assignment as a teacher, simply log in to
                            your account and navigate to the 'Create Assignment'
                            section. Fill in the required details such as the
                            assignment title, description, and due date, and click on
                            the 'Create' button.
                            </span>
                        </span>
                        </div><br><br>
                        <div class="question1-container">
                        <span class="question1-text heading3">
                            <span><b>How can students upload their assignments?</b></span>
                        </span><br>
                        <span class="bodySmall">
                            <span>
                            Students can upload their assignments by logging in to
                            their accounts and going to the 'Upload Assignment'
                            section. They need to select the assignment they want to
                            submit, attach the necessary files, and click on the
                            'Submit' button.
                            </span>
                        </span>
                        </div><br><br>
                        <div class="question1-container">
                        <span class="question1-text heading3">
                            <span><b>
                            Can I edit or delete an assignment after it has been
                            created?</b>
                            </span>
                        </span><br>
                        <span class="bodySmall">
                            <span>
                            Yes, as a teacher, you have the ability to edit or delete
                            an assignment even after it has been created. Simply go to
                            the 'Manage Assignments' section, find the assignment you
                            want to modify or remove, and use the respective options
                            provided.
                            </span>
                        </span>
                        </div><br><br>
                        <div class="question1-container">
                        <span class="question1-text heading3">
                            <span><b>
                            What file formats are supported for assignment uploads?</b>
                            </span>
                        </span><br>
                        <span class="bodySmall">
                            <span>
                            Our platform supports a wide range of file formats for
                            assignment uploads. You can upload files in formats such
                            as .doc, .docx, .pdf, .ppt, .pptx, .xls, .xlsx, .txt, and
                            more.
                            </span>
                        </span>
                        </div><br><br>
                        <div class="question1-container">
                        <span class="question1-text heading3">
                            <span><b>
                            How can I contact the admin for any issues or queries?</b>
                            </span>
                        </span><br>
                        <span class="bodySmall">
                            <span>
                            If you have any issues or queries regarding the website or
                            its functionality, you can contact the admin by sending an
                            email to officialproject232@gmail.com. Our team will respond
                            to your queries as soon as possible.
                            </span>
                        </span>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
    </div>
    <div class="footer">
        <span>&copy; UYA, All rights reserved.</span>
    </div>
</body>
</html>
