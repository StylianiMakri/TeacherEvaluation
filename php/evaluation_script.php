<?php
session_start();

// Database connection
$conn = mysqli_connect("localhost", "root", "", "teacherevaluationsystem");

// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

// Get the code from the form
$code = $_POST['code'];

// Retrieve the teacher ID associated with the code
$sql = "SELECT id FROM teachers WHERE code = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $code);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$teacher_id = $row['id'];

// Retrieve the questions associated with the teacher ID
$sql = "SELECT * FROM evaluationquestions WHERE teacher_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $teacher_id);
$stmt->execute();
$result = $stmt->get_result();

// After fetching the teacher_id from the database
if ($result->num_rows > 0) {
  $row = $result->fetch_assoc();
  $teacher_id = $row['id'];
} else {
  // Handle the situation where the teacher_id is not found
  die("Invalid code provided. Teacher not found.");
} 

// Start the form
echo '<form action="../php/save_answers_script.php" method="post">';

while ($row = $result->fetch_assoc()) {
  echo "<p>" . $row['question'] . "</p>";
  echo "<select name='" . $row['id'] . "'>";
  for ($i = 1; $i <= 5; $i++) {
      echo "<option value='$i'>$i</option>";
  }
  echo "</select><br>";
}

echo '<button type="submit">Submit Answers</button>';
echo '</form>';


// Close the connection
mysqli_close($conn);
?>
