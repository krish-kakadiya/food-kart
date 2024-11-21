<?php
session_start();

// Destroy the session to log out
session_destroy();

// Redirect to the login page
header('Location: admin_login.php');
exit;
?>
