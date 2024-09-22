<?php
session_start(); // Start the session
session_destroy(); // Destroy all session data

// Redirect the user to the welcome page after logging out
header("Location: welcome.php");
exit();
?>
