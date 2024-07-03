<?php
session_start();

session_unset();

// Destroy the session
session_destroy();

// Redirect to the login page or any other page as needed
header("Location: login.php");
exit();
?>
