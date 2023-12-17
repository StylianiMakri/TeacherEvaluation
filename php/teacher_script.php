<?php
session_start();

$conn = mysqli_connect("localhost", "root", "", "teacherevaluationsystem");

// Check connection
if (!$conn) {
   die("Connection failed: " . mysqli_connect_error());
}

// Get teacher id
$teacher_id = $_SESSION['user_id']; // Assuming the session variable for user id is 'user_id'

// Generate a random code
function generateCode($size = 10)
{
   $chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVZYabcdefghijklmnopqrstuvwxyz';
   $code = substr(str_shuffle($chars), 0, $size);
   return $code;
}

$code = generateCode();

// Check if the code already exists in the database
do {
   $stmt = $conn->prepare("SELECT count(id) from teachers where code = ?");
   $stmt->bind_param("s", $code);
   $stmt->execute();
   $result = $stmt->get_result();
   $row_code = $result->fetch_row();
} while ($row_code[0] != 0);

// Update the teacher's record with the new code
$stmt = $conn->prepare("UPDATE teachers SET code = ? WHERE id = ?");
$stmt->bind_param("si", $code, $teacher_id);
$stmt->execute();

// Create a .txt file with the code
$file = fopen("code.txt", "w");
fwrite($file, $code);
fclose($file);

// Redirect to the teacher page
header("Location: ../html/teacher.html");
?>
