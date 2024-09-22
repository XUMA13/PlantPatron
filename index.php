<?php
session_start();  // Start session to manage user logins
include 'db.php';  // Assuming 'db.php' is the file with your database connection

// Check if the user is logged in (assumes you store user ID in session upon login)
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");  // Redirect to login page if not logged in
    exit();
}

// Fetch the logged-in user's name (optional, for a personalized greeting)



$stmt = $conn->prepare("SELECT NAME FROM PLANT_OWNER WHERE ID = ?");
$stmt->bind_param('i', $user_id);
$stmt->execute();
$plants = $stmt->get_result();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plant Patron Homepage</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css">
    
</head>
<body>

<div class="container my-5">
        <h1>Welcome to Plant Patron, <?= $_SESSION['name']; ?>!</h1>
        <p>Please choose an option below:</p>
        <div class="mt-3">
            <a href="plant_list.php" class="btn btn-primary">View Plant List</a>
            
        </div>
    </div>
</body>
</html>

  
