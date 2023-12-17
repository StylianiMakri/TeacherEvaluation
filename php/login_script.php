<?php
$conn = mysqli_connect("localhost","root" ,"" , "teacherevaluationsystem");

// Check connection
if (!$conn) {
   die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
   // Get form data
   $type = $_POST['type'];
   $email = $_POST['email'];
   $password = $_POST['password'];
 
   // Determine which table to check based on user type
   $table = ($type == 'student') ? 'students' : 'teachers';
 
   // Prepare a select statement
   $sql = "SELECT * FROM $table WHERE email = ?";
   
   if($stmt = mysqli_prepare($conn, $sql)){
       mysqli_stmt_bind_param($stmt, "s", $param_email);
       $param_email = $email;
 
       // Attempt to execute the prepared statement
       if(mysqli_stmt_execute($stmt)){
           // Store result
           mysqli_stmt_store_result($stmt);
           
           // Check if email exists, if yes then verify password
           if(mysqli_stmt_num_rows($stmt) == 1){                
               // Bind result variables
               if($table == 'students') {
                  mysqli_stmt_bind_result($stmt, $id, $email, $hashed_password, $name, $lastname, $dateofbirth, $yearsofstudy, $phone);
               } else {
                  mysqli_stmt_bind_result($stmt, $id, $email, $hashed_password, $name, $lastname, $phone, $code);
               }
               if(mysqli_stmt_fetch($stmt)){
                 if(password_verify($password, $hashed_password)){
                     // Password is correct
                     echo "Login successful. Welcome " . $email . "!";
                 } else{
                     // Display an error message if password is not valid
                     echo "The password you entered was not valid.";
                 }
               }
           } else{
               // Display an error message if email doesn't exist
               echo "No account found with that email.";
           }
       } else{
           echo "Oops! Something went wrong. Please try again later.";
       }
   }
 
   // Close statement
   mysqli_stmt_close($stmt);
 
   // Close connection
   mysqli_close($conn);
 }
 ?>
