<?php
// Start session
session_start();

// Unset all session variables
session_unset();

// Destroy the session
session_destroy();

// Redirect to restaurant login page
header("Location: ../backend/restaurant_login.php");  // Adjust the path accordingly
exit();
?>
