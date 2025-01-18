<?php
include("connection.php");

if(!isset($_SESSION['adminid'])){
    header("Location:ADlogin.php");
    die();
}

if(isset($_POST['Submit'])){
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

    $host = "localhost";
    $username = "root";
    $password = "";
    $database = "administrator";

    //Creating db connection
    $con = mysqli_connect($host, $username, $password, $database);

    $sql = "INSERT INTO student_data(std_name,std_email,gender,address1,address2,std_id,pass,branch,branch_id,phone,std_picture) values ('$std_name','$std_email','$gender','$address1','$address2','$std_id','$pass','$branch','$branch_id','$phone','$std_picture')";
    $result = mysqli_query($con,$sql);
    if($result){
      header("Location: managestd.php");
      $message = "Student added successfully!";
    }else{
      die(mysqli_error($con));
    }
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
  }
  .container {
    margin-top: 10px;
    position: relative;
    left: 30%;
    max-width: 700px;
    width: 100%;
    background: #d3d3d3;
    padding: 25px;
    border-radius: 8px;
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
  }
  .container header {
    font-size: 1.5rem;
    color: #333;
    font-weight: 500;
    text-align: center;
  }
  .container .form {
    margin-top: 30px;
  }
  .form .input-box {
    width: 100%;
    margin-top: 20px;
  }
  .input-box label {
    color: #333;
  }
  .form :where(.input-box input, .select-box) {
    position: relative;
    height: 50px;
    width: 100%;
    outline: none;
    font-size: 1rem;
    color: #707070;
    margin-top: 8px;
    border: 1px solid #ddd;
    border-radius: 6px;
    padding: 0 15px;
  }
  .input-box input:focus {
    box-shadow: 0 1px 0 rgba(0, 0, 0, 0.1);
  }
  .form .column {
    display: flex;
    column-gap: 15px;
  }
  .form .gender-box {
    margin-top: 20px;
  }
  .gender-box h3 {
    color: #333;
    font-size: 1rem;
    font-weight: 400;
    margin-bottom: 8px;
  }
  .form :where(.gender-option, .gender) {
    display: flex;
    align-items: center;
    column-gap: 50px;
    flex-wrap: wrap;
  }
  .form .gender {
    column-gap: 5px;
  }
  .gender input {
    accent-color: rgb(130, 106, 251);
  }
  .form :where(.gender input, .gender label) {
    cursor: pointer;
  }
  .gender label {
    color: #707070;
  }
  .address :where(input, .select-box) {
    margin-top: 15px;
  }
  .select-box select {
    height: 100%;
    width: 100%;
    outline: none;
    border: none;
    color: #707070;
    font-size: 1rem;
  }
  #submit {
    height: 55px;
    width: 100%;
    color: #fff;
    font-size: 1rem;
    font-weight: 400;
    margin-top: 30px;
    border: none;
    cursor: pointer;
    transition: all 0.2s ease;
    background: rgb(130, 106, 251);
  }
  #submit:hover {
    background: rgb(88, 56, 250);
  }
  #img{
    padding-top: 10px;
  }
  #img:hover{
    background: #d3d3d3;
  }
  #img::-webkit-file-upload-button{
    visibility: hidden;
  }
  #img::before{
    content: 'Student picture';
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

  /*Responsive*/
  @media screen and (max-width: 500px) {
    .form .column {
      flex-wrap: wrap;
    }
    .form :where(.gender-option, .gender) {
      row-gap: 15px;
    }
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
    <section class="container">
      <header>Add Student Details</header>
      <form action="std-notify.php" class="form" method="POST" enctype="multipart/form-data">
        <div class="input-box">
          <label>Full Name</label>
          <input type="text" name="std_name" style="text-transform:capitalize;" placeholder="Enter full name" required />
        </div>

        <div class="input-box">
          <label>Email Address</label>
          <input type="text" name="std_email" placeholder="Enter email address" required />
        </div>

        <div class="column">
          <div class="input-box">
            <label>Phone Number</label>
            <input type="number" name="phone" placeholder="Enter phone number" required />
          </div>
          
        </div>
        <div class="gender-box">
          <h3>Gender</h3>
          <div class="gender-option">
            <div class="gender">
              <input type="radio" id="check-female" name="gender" value="Female" checked />Female
              
            </div>
            <div class="gender">
              <input type="radio" id="check-male" name="gender" value="Male" />Male
              
            </div>
            <div class="gender">
              <input type="radio" id="check-other" name="gender" value="Other" />Other
        
            </div>
          </div>
        </div>
        <div class="input-box address">
          <label>Address</label>
          <input type="text" name="address1" style="text-transform:capitalize;" placeholder="Enter street address" required />
          <input type="text" name="address2" style="text-transform:capitalize;" placeholder="Enter street address line 2" required />
          <div class="column">
            <div class="select-box">
              <select name="branch" required>
                <option hidden>Branch</option>
                <option value="Computer Science">Computer Science</option>
                <option value="Electronics">Electronics</option>
                <option value="Mechanical">Mechanical</option>
              </select>
            </div>
          </div>
          <div class="column">
            <input type="text" name="branch_id" placeholder="Enter branch id" required />
            <input type="file" accept="image/*" id="img" name="std_picture" placeholder="Enter student image" required />
          </div>
          <div class="column">
            <input type="text" name="std_id" style="text-transform:capitalize;" placeholder="Enter user id" required />
            <input type="text" name="pass" placeholder="Enter user password" required />
        </div>
        <button id="submit" name="Submit">Submit</button>
      </form>
    </section>
    <div class="footer">
        <span>&copy; UYA, All rights reserved.</span>
    </div>
  </body>
</html>