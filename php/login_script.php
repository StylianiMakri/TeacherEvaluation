<?php
session_start();

$conn = mysqli_connect("localhost", "root", "", "teacherevaluationsystem");

if (!$conn) {
   die("Connection failed: " . mysqli_connect_error());
}

$type = $_POST['type'];
$email = $_POST['email'];
$password = $_POST['password'];

$table = ($type == 'student') ? 'students' : 'teachers';

$sql = "SELECT id, email, password FROM $table WHERE email = ?";

if ($stmt = mysqli_prepare($conn, $sql)) {
   mysqli_stmt_bind_param($stmt, "s", $param_email);
   $param_email = $email;

   if (mysqli_stmt_execute($stmt)) {
      mysqli_stmt_store_result($stmt);

      if (mysqli_stmt_num_rows($stmt) == 1) {
         mysqli_stmt_bind_result($stmt, $id, $email, $hashed_password);
         if (mysqli_stmt_fetch($stmt)) {
            if (password_verify($password, $hashed_password)) {
               session_start();
               $_SESSION['user_id'] = $id;
               if ($type == 'student') {
                  header("Location: ../html/students.html");
               } else {
                  header("Location: ../html/teacher.html");
               }
            } else {
               echo "The password you entered was not valid.";
            }
         }
      } else {
         echo "No account found with that email.";
      }
   } else {
      echo "Oops! Something went wrong. Please try again later.";
   }
}

mysqli_stmt_close($stmt);

mysqli_close($conn);
?>
