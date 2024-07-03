<?php
session_start();
require '../db.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$quiz_id = $_GET['id'];

$stmt = $conn->prepare("SELECT id, question_text, option1, option2, option3, option4, correct_option FROM questions WHERE quiz_id = ?");
$stmt->bind_param("i", $quiz_id);
$stmt->execute();
$result = $stmt->get_result();
$questions = $result->fetch_all(MYSQLI_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $score = 0;
    foreach ($questions as $index => $question) {
        $selected_option = $_POST["question_$index"];
        if ($selected_option == $question['correct_option']) {
            $score++;
        }
    }
    $total_questions = count($questions);
    $_SESSION['score'] = $score;
    $_SESSION['total_questions'] = $total_questions;
    header("Location: quiz_result.php?id=$quiz_id");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Take Quiz</title>
    <link rel="stylesheet" href="../CSS/quiz.css">
</head>
<body>
    <div class="main">
        <header>
            <div class="welcome">
                <h1>Take Quiz</h1>
            </div>
            <div class="logout">
                <button name="logout" onClick="logout()">Logout</button>
            </div>
        </header>
        <div class="container">
            <form method="post" action="take_quiz.php?id=<?php echo $quiz_id; ?>">
                <?php foreach ($questions as $index => $question): ?>
                    <div class="question">
                        <p style="font-size: 1.3rem"><?php echo htmlspecialchars($question['question_text']); ?></p>
                        <label>
                            <input style="margin-top:10px" type="radio" name="question_<?php echo $index; ?>" value="1" required>
                            <?php echo htmlspecialchars($question['option1']); ?>
                        </label><br>
                        <label>
                            <input type="radio" name="question_<?php echo $index; ?>" value="2" required>
                            <?php echo htmlspecialchars($question['option2']); ?>
                        </label><br>
                        <label>
                            <input type="radio" name="question_<?php echo $index; ?>" value="3" required>
                            <?php echo htmlspecialchars($question['option3']); ?>
                        </label><br>
                        <label>
                            <input type="radio" name="question_<?php echo $index; ?>" value="4" required>
                            <?php echo htmlspecialchars($question['option4']); ?>
                        </label><br>
                    </div>
                <?php endforeach; ?>
                <button type="submit">Submit Quiz</button>
            </form>
        </div>
    </div>

    <script>
        function logout() {
            window.location.href = "../logout.php";
        }
    </script>
</body>
</html>
