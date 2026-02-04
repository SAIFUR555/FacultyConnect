<?php
session_start();

// Destroy the session to log out the user
session_unset();
session_destroy();

// Redirect to the login page
header('Location: /springwtj/FacultyConnect/App/View/auth/login.php');
exit;
?>
