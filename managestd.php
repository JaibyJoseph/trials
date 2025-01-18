<?php
include ('deletee.php');  
$query = "select * from student_data";  
$run = mysqli_query($con,$query);

if(!isset($_SESSION['adminid'])){
   header("Location:ADlogin.php");
   die();
}

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
<!---Coding By CodingLab | www.codinglabweb.com--->
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>ADD STUDENTS</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-DREx51xQ4tn0ZtydXgFYbs0ax2Uj9s4PQ91wVRdztJZbsAw2OqmTxxRDk4NzhVvtt5zfDsdPcUL2P4h0IhC0yQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <!--<title>Registration Form in HTML CSS</title>-->
    <!---Custom CSS File--->
    <link rel="stylesheet" href="style.css" />
  </head>
  <style>
    /* Import Google font - Poppins */
  @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap");
  * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: "Poppins", sans-serif;
  }
  body {
    min-height: 100vh;
    background: #fff;
    overflow: hidden;
  }
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
  #sidebar{
    width: 250px;
    background-color: #333;
    color: #fff;
    position: fixed;
    top: 0px;
    left: 0px; 
    height: 100vh;
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
  .back_btn{
        position: absolute;
        width: 30px;
        margin-top: 5px;
        left: 17%;
        border-radius: 5px; 
    }
  .next_btn{
        position: absolute;
        width: 30px;
        margin-top: 5px;
        left: 19%;
        border-radius: 5px;
    }
    table{
        width: 70%;
        position: relative;
        top: 70%;
        margin-top: 80px;
        left: 20%;
        text-align: center;
    }
    .heading{
        background-color: #d3d3d3;
    }
    .heading th{  
        padding: 10px 0;  
    }
    .data td{  
        padding: 15px 0;  
    }
    #adding{
        position: relative;
        top: 70px;
        left: 20%;
        font-size: 18px;
    }
    #adding a{
        color: black;
        text-decoration: underline;
    }
    #btn{  
        text-decoration: none;  
        color: black;  
        background-color: #e74c3c;  
        padding: 5px 20px;  
        border-radius: 3px;  
    }  
    #btn:hover{  
        background-color: #c0392b;  
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
        </div>
    </div>

    <!-- Forward and Backward Buttons -->
    <button class="back_btn" onclick="goBack()"><i class='bx bxs-left-arrow-alt'></i></button>
    <button class="next_btn" onclick="goForward()"><i class='bx bxs-right-arrow-alt'></i></button>
    
        <script>
            function goBack() {
                window.history.back();
            }
            function goForward() {
                window.history.forward();
            }
        </script>

    <div id="adding">
        <a href="addstudents.php">
            <span>&plus; ADD NEW STUDENT</span>
        </a>
    </div>
    
    <table border="1" cellspacing="0" cellpadding="0">  
      <tr class="heading">  
           <th>Sr. No.</th>  
           <th>ID</th>  
           <th>Name</th>  
           <th>View Profile</th>
           <th>Delete</th>    
      </tr>  
      <?php   
      $i=1;  
           if ($num = mysqli_num_rows($run)>0) {  
                while ($result = mysqli_fetch_assoc($run)) {  
                     echo "  
                          <tr class='data'>  
                               <td>".$i++."</td>  
                               <td>".$result['std_id']."</td>  
                               <td>".$result['std_name']."</td>
                               <td><a href='viewstudent.php?std_id=".$result['std_id']."' id='btn'>View profile</a></td>
                               <td><a href='deletee.php?std_id=".$result['std_id']."' id='btn'>Delete</a></td> 
                                
                          </tr>  
                     ";  
                }  
           }  
      ?>  
    </table>

    <div class="footer">
        <span>&copy; UYA, All rights reserved.</span>
    </div>
  </body>
</html>
