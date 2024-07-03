<?php
session_start();
require '../db.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $created_by = $_SESSION['user']; // Assume you store the user ID in the session

    // Insert into quizzes table
    $stmt = $conn->prepare("INSERT INTO quizzes (title, description, created_by) VALUES (?, ?, ?)");
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("ssi", $title, $description, $created_by);
    if (!$stmt->execute()) {
        die("Execute failed: " . $stmt->error);
    }
    $quiz_id = $stmt->insert_id;

    // Insert each question into questions table
    foreach ($_POST['questions'] as $question) {
        $question_text = $question['question_text'];
        $option1 = $question['option1'];
        $option2 = $question['option2'];
        $option3 = $question['option3'];
        $option4 = $question['option4'];
        $correct_option = $question['correct_option'];

        $stmt = $conn->prepare("INSERT INTO questions (quiz_id, question_text, option1, option2, option3, option4, correct_option) VALUES (?, ?, ?, ?, ?, ?, ?)");
        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }
        $stmt->bind_param("issssss", $quiz_id, $question_text, $option1, $option2, $option3, $option4, $correct_option);
        if (!$stmt->execute()) {
            die("Execute failed: " . $stmt->error);
        }
    }

    header("Location: ../index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Quiz</title>
    <link rel="stylesheet" href="../CSS/quiz.css">
    <script src="quiz_creation.js"></script>
</head>
<body>
    <div class="main">
        <header>
            <div class="welcome">
                <h1>Create a New Quiz</h1>
            </div>
            <div class="logout">
                <button name="logout" onClick="logout()">Logout</button>
            </div>
        </header>
        <div class="container">
            <form method="post" action="create_quiz.php">
                <label for="title">Title<span style="color:red;">*</span></label>
                <input type="text" name="title" required><br>

                <label for="description">Description<span style="color:red;">*</span></label>
                <input type="text" name="description" required><br>

                <div id="questions">
                    <h2>Questions</h2>
                    <div class="question">
                        <label for="question_text">Question<span style="color:red;">*</span></label>
                        <input type="text" name="questions[0][question_text]" required><br>

                        <label for="option1">Option 1<span style="color:red;">*</span></label>
                        <input type="text" name="questions[0][option1]" required><br>

                        <label for="option2">Option 2<span style="color:red;">*</span></label>
                        <input type="text" name="questions[0][option2]" required><br>

                        <label for="option3">Option 3<span style="color:red;">*</span></label>
                        <input type="text" name="questions[0][option3]" required><br>

                        <label for="option4">Option 4<span style="color:red;">*</span></label>
                        <input type="text" name="questions[0][option4]" required><br>

                        <label for="correct_option">Correct Option<span style="color:red;">*</span></label>
                        <input type="number" name="questions[0][correct_option]" min="1" max="4" required><br>
                    </div>
                </div>
                <div class="btn">
                    <button type="button" onclick="addQuestion()">Add Another Question</button><br>
                    <button type="submit">Create Quiz</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function logout() {
            window.location.href = "../logout.php";
        }

        function addQuestion() {
            const questionsDiv = document.getElementById('questions');
            const questionCount = document.querySelectorAll('.question').length;
            const questionHTML = `
                <div class="question">
                    <label for="question_text">Question:</label>
                    <input type="text" name="questions[${questionCount}][question_text]" required><br>

                    <label for="option1">Option 1:</label>
                    <input type="text" name="questions[${questionCount}][option1]" required><br>

                    <label for="option2">Option 2:</label>
                    <input type="text" name="questions[${questionCount}][option2]" required><br>

                    <label for="option3">Option 3:</label>
                    <input type="text" name="questions[${questionCount}][option3]" required><br>

                    <label for="option4">Option 4:</label>
                    <input type="text" name="questions[${questionCount}][option4]" required><br>

                    <label for="correct_option">Correct Option:</label>
                    <input type="number" name="questions[${questionCount}][correct_option]" min="1" max="4" required><br>
                </div>
            `;
            questionsDiv.insertAdjacentHTML('beforeend', questionHTML);
        }
    </script>
</body>
</html>
