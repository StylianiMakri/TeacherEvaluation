<?php
session_start();

// Database connection
$conn = mysqli_connect("localhost", "root", "", "teacherevaluationsystem");

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get the code from the form
$code = isset($_POST['code']) ? $_POST['code'] : '';

// Retrieve the questions
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

// Start the form
echo '<form action="../php/save_answers_script.php" method="post">';

// Display the questions to the student
while ($row = $result->fetch_assoc()) {
    echo "<p>" . $row['question'] . "</p>";
    echo "<select name='answers[{$row['id']}]'>";
    for ($i = 1; $i <= 5; $i++) {
        echo "<option value='$i'>$i</option>";
    }
    echo "</select><br>";
}

// Add a hidden input for the code
echo "<input type='hidden' name='code' value='$code'>";

// Add a submit button
echo '<button type="submit">Submit Answers</button>';

echo '</form>';

// Close the connection
mysqli_close($conn);
?>
