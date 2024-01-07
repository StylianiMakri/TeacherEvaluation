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
 
  // Check if a row was returned
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $id = $row['id'];
}

  // Retrieve the questions
  $sql = "SELECT * FROM evaluationquestions";
  $stmt = $conn->prepare($sql);
  $stmt->execute();
  $result = $stmt->get_result();

   // Start the form
 echo '<form action="../php/save_answers_script.php" method="post">';
 
  // Display the questions to the student
  while ($row = $result->fetch_assoc()) {
      echo "<p>" . $row['question'] . "</p>";
      echo "<select name='answer'>";
      for ($i = 1; $i <= 5; $i++) {
          echo "<option value='$i'>$i</option>";
      }
      echo "</select><br>";
  }

  // Close the connection
  mysqli_close($conn);
  ?>
