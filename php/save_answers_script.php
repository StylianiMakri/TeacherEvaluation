<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

$conn = mysqli_connect("localhost", "root", "", "teacherevaluationsystem");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$code = isset($_POST['code']) ? $_POST['code'] : '';

$answers = isset($_POST['answers']) ? $_POST['answers'] : [];

foreach ($answers as $question_id => $answer) {
    $check_question_exists = "SELECT id FROM evaluationquestions WHERE id = ?";
    $stmt_check_question = $conn->prepare($check_question_exists);
    $stmt_check_question->bind_param("i", $question_id);
    $stmt_check_question->execute();
    $result_check_question = $stmt_check_question->get_result();

    if ($result_check_question->num_rows > 0) {
        $sql = "INSERT INTO answers (student_id, question_id, answer, code) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiis", $_SESSION['user_id'], $question_id, $answer, $code);

        if (!$stmt->execute()) {
            echo "Error: " . $stmt->error;
        }
    } else {
        echo "Error: Invalid question_id - $question_id";
    }
}

mysqli_close($conn);
header("Location: thankyou.php");
exit(); 
?>
