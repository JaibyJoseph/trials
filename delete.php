<?php   
 include ('connection.php');  
 if (isset($_GET['userid'])) {  
      $userid = $_GET['userid'];  
      $query = "DELETE FROM `teacher` WHERE userid = '$userid'";  
      $run = mysqli_query($con,$query);  
      if ($run) {  
           header('location:manageteach.php');  
      }else{  
           echo "Error: ".mysqli_error($con);  
      }  
 }  
 ?>