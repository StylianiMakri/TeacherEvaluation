<?php
session_start();

$conn = mysqli_connect("localhost", "root", "", "teacherevaluationsystem");

if (!$conn) {
   die("Connection failed: " . mysqli_connect_error());
}

$teacher_id = $_SESSION['user_id'];

function generateCode($size = 10)
{
   $chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVZYabcdefghijklmnopqrstuvwxyz';
   $code = substr(str_shuffle($chars), 0, $size);
   return $code;
}

$code = generateCode();

do {
   $stmt = $conn->prepare("SELECT count(id) from teachers where code = ?");
   $stmt->bind_param("s", $code);
   $stmt->execute();
   $result = $stmt->get_result();
   $row_code = $result->fetch_row();
} while ($row_code[0] != 0);

$stmt = $conn->prepare("UPDATE teachers SET code = ? WHERE id = ?");
$stmt->bind_param("si", $code, $teacher_id);
$stmt->execute();

$file = fopen("code.txt", "w");
fwrite($file, $code);
fclose($file);

header("Location: ../html/teacher.html");
?>
