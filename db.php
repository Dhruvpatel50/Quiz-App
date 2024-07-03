<?php
$servername = "localhost:3304";
$username = "root";
$password = "";
$dbname = "quiz_maker";

$conn = mysqli_connect($servername, $username, $password);
$db = mysqli_select_db($conn, $dbname);

$pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
if (!$db) {
    die("Connection failed: " . $conn->connect_error);
}
?>
