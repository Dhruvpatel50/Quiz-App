<?php
session_start();
require '../db.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

// Fetch quizzes
$stmt = $conn->prepare("SELECT id, title, description, created_by FROM quizzes");
$stmt->execute();
$result = $stmt->get_result();
$quizzes = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Available Quizzes</title>
    <link rel="stylesheet" href="../CSS/list_quiz.css">
</head>
<body>
    <div class="main">
        <header>
            <div class="welcome">
                <h1>Available Quizzes</h1>
            </div>
            <div class="logout">
                <button name="logout" onClick="logout()">Logout</button>
            </div>
        </header>
        <div class="container">
            <ul>
                <?php foreach ($quizzes as $quiz): ?>
                    <li>
                        <h2><?php echo htmlspecialchars($quiz['title']); ?></h2>
                        <p><?php echo htmlspecialchars($quiz['description']); ?></p>
                        <p><em>Created by: <?php 
                                            $name = $pdo->prepare("Select username from users where id = ?");
                                            $name->execute([$quiz['created_by']]);
                                            $user = $name->fetch(PDO::FETCH_ASSOC);
                                            $username = $user['username'];
                                            echo htmlspecialchars($username); ?></em></p>
                        <a href="take_quiz.php?id=<?php echo $quiz['id']; ?>">Take Quiz</a>
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
