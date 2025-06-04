<?php
session_start();

$conn = mysqli_connect("localhost", "root", "", "teacherevaluationsystem");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$code = isset($_POST['code']) ? $_POST['code'] : '';

$sql = "SELECT * FROM evaluationquestions";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Answer Questions</title>
    <link rel="stylesheet" type="text/css" href="../css/student_styles.css">
</head>
<body>

<form action="../php/save_answers_script.php" method="post">

<?php

echo '<form action="../php/save_answers_script.php" method="post">';

while ($row = $result->fetch_assoc()) {
    echo "<p>" . $row['question'] . "</p>";
    echo "<select name='answers[{$row['id']}]'>";
    for ($i = 1; $i <= 5; $i++) {
        echo "<option value='$i'>$i</option>";
    }
    echo "</select><br>";
}

echo "<input type='hidden' name='code' value='$code'>";

echo '<button type="submit">Submit Answers</button>';

echo '</form>';

mysqli_close($conn);
?>
