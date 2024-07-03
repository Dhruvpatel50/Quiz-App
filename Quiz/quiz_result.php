<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$quiz_id = $_GET['id'];
$score = $_SESSION['score'];
$total_questions = $_SESSION['total_questions'];

require '../db.php';

// Fetch quiz questions and correct answers
$stmt = $conn->prepare("SELECT * FROM questions WHERE quiz_id = ?");
$stmt->bind_param("i", $quiz_id);
$stmt->execute();
$result = $stmt->get_result();
$questions = $result->fetch_all(MYSQLI_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Quiz Results</title>
    <link rel="stylesheet" href="../CSS/quiz.css">
</head>
<body>
    <div class="main">
        <header>
            <div class="welcome">
                <h1>Quiz Results</h1>
            </div>
            <div class="logout">
                <button name="logout" onClick="logout()">Logout</button>
            </div>
        </header>
        <div class="container">
            <p>Your Score: <?php echo $score; ?> out of <?php echo $total_questions; ?></p>
            <h2>Correct Answers</h2>
            <ul>
                <?php foreach ($questions as $index => $question): ?>
                    <li>
                        <p><?php echo htmlspecialchars($question['question_text']); ?></p>
                        <p>Correct Answer: <?php 
                        $correct = ($question['correct_option']);
                        echo htmlspecialchars($question['option'.$correct]); ?></p>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>

    <script>
        function logout() {
            window.location.href = "../logout.php";
        }
    </script>
</body>
</html>
