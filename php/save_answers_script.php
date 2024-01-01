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
   $sql = "INSERT INTO answers (student_id, question_id, answer) VALUES (?, ?, ?)";
   $stmt = $conn->prepare($sql);
   $stmt->bind_param("iii", $_SESSION['user_id'], $question_id, $answer);
   $stmt->execute();
}


 // Close the connection
 mysqli_close($conn);
 ?>
