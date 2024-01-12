<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

// Database connection
$conn = mysqli_connect("localhost", "root", "", "teacherevaluationsystem");

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get the code from the form
$code = isset($_POST['code']) ? $_POST['code'] : '';

// Get the answers from the form
$answers = isset($_POST['answers']) ? $_POST['answers'] : [];

// Insert the answers into the database
foreach ($answers as $question_id => $answer) {
    // Check if the question_id exists in the evaluationquestions table
    $check_question_exists = "SELECT id FROM evaluationquestions WHERE id = ?";
    $stmt_check_question = $conn->prepare($check_question_exists);
    $stmt_check_question->bind_param("i", $question_id);
    $stmt_check_question->execute();
    $result_check_question = $stmt_check_question->get_result();

    if ($result_check_question->num_rows > 0) {
        // The question_id exists, proceed with the insertion
        $sql = "INSERT INTO answers (student_id, question_id, answer, code) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiis", $_SESSION['user_id'], $question_id, $answer, $code);

        // Check for errors
        if (!$stmt->execute()) {
            echo "Error: " . $stmt->error;
        }
    } else {
        echo "Error: Invalid question_id - $question_id";
    }
}

// Close the connection
mysqli_close($conn);
// Redirect to the thank-you page
header("Location: thankyou.php");
exit(); // Ensure that no further code is executed after the header() call
?>
