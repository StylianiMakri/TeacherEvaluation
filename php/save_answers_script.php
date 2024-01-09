<?php
 session_start();

 // Database connection
 $conn = mysqli_connect("localhost", "root", "", "teacherevaluationsystem");

 // Check connection
 if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
 }

// Get the answers from the form
$answers = $_POST;

// Insert the answers into the database
foreach ($answers as $question_id => $answer) {
   $check_teacher_id = "SELECT id FROM teachers WHERE id = ?";
   $stmt_check = $conn->prepare($check_teacher_id);
   $stmt_check->bind_param("i", $teacher_id);
   $stmt_check->execute();
   $result = $stmt_check->get_result();
  
      $sql = "INSERT INTO answers (student_id, teacher_id, question_id, answer) VALUES (?, ?, ?, ?)";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("iiis", $_SESSION['user_id'], $teacher_id, $question_id, $answer);
      $stmt->execute();
  
  }
  
 // Close the connection
 mysqli_close($conn);
 ?>
