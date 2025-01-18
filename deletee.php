
<?php 
#this is to delete student data  
 include ('connection.php');  
 if (isset($_GET['std_id'])) {  
      $std_id = $_GET['std_id'];  
      $query = "DELETE FROM `student_data` WHERE std_id = '$std_id'";  
      $run = mysqli_query($con,$query);  
      if ($run) {  
           header('location:managestd.php');  
      }else{  
           echo "Error: ".mysqli_error($con);  
      }  
 }  
 ?>
