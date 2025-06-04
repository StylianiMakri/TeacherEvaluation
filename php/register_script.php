<?php
$conn = mysqli_connect("localhost","root" ,"" , "teacherevaluationsystem");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$type = $_POST['type'];
$email = $_POST['email'];
$password = $_POST['password'];
$name = $_POST['name'];
$lastname = $_POST['lastname'];
$dateofbirth = $_POST['dateofbirth'];
$yearofstudy = $_POST['yearofstudy'];
$phone = $_POST['phone'];

$hashed_password = password_hash($password, PASSWORD_DEFAULT);

if ($type == 'student') {
    $sql = "INSERT INTO students (email, password, name, lastname, dateofbirth, yearofstudy, phone) VALUES ('$email', '$hashed_password', '$name', '$lastname', '$dateofbirth', '$yearofstudy', '$phone')";
} else {
    $sql = "INSERT INTO teachers (email, password, name, lastname, dateofbirth, phone) VALUES ('$email', '$hashed_password', '$name', '$lastname', '$dateofbirth', '$phone')";
}

if (mysqli_query($conn, $sql)) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

mysqli_close($conn);
?>
