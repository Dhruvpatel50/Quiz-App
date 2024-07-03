<?php
session_start();
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->execute([$username, $email, $password]);

    $id = $pdo->prepare("select id from users where username = ?");
    $id->execute([$username]);
    $id = $id->fetch(PDO::FETCH_ASSOC)['id'];

    $_SESSION['user'] = $id;
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - Quiz Maker</title>
    <link rel="stylesheet" href="CSS/style2.css">
</head>
<body>
    <div class="main">
        <header>
            <div class="welcome">
                <h1>Register for Quiz Maker</h1>
            </div>
        </header>
        <div class="container">
            <div class="form-container">
                <form method="post" action="register.php" id="registerForm">
                    <div class="form-group">
                        <label for="username">Username:</label>
                        <input type="text" id="username" name="username" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    <button type="submit">Register</button>
                    <div class="register">Already have an account? <a href="register.php">Login</a></div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
