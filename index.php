<?php
session_start();
require 'db.php';
if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}

$user = $_SESSION['user'];
$stmt = $pdo->prepare("SELECT username FROM users WHERE id = ?");
$stmt->execute([$user]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
$username = $user['username'];

?>

<!DOCTYPE html>
<html>
<head>
    <title>Quiz Maker</title>
    <link rel="stylesheet" type="text/css" href="CSS/index.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <div class="main">
        <header>
            <div class="logout">
                <button name="logout" onClick="logout()">Logout</button>
            </div>
            <div class="welcome">
                <h1>Welcome to Quiz Maker, <?php echo htmlspecialchars($username); ?></h1>
            </div>
        </header>
        <div class="container">
            <button class="btn" onClick="create_quiz()">Create a Quiz</button>
            <button class="btn" onClick="quiz_list()">Take a Quiz</button>
        </div>
    </div>

    <script>
        function logout() {
            window.location.href = "logout.php";
        }
        function create_quiz() {
            window.location.href = "Quiz/create_quiz.php";
        }
        function quiz_list() {
            window.location.href = "Quiz/list_quiz.php";
        }
    </script>
</body>
</html>
