<?php
session_start();
include 'db.php'; // Include the database connection file

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login page if the user is not logged in
    exit();
}

// Fetch user's name for the greeting (assuming the user’s name is stored in the session or can be fetched from the database)
$user_id = $_SESSION['user_id'];

// Fetch the user's name from the database
$stmt = $conn->prepare("SELECT NAME FROM PLANT_OWNER WHERE ID = ?");
$stmt->bind_param('i', $user_id);
$stmt->execute();
$stmt->bind_result($user_name);
$stmt->fetch();
$stmt->close();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PlantPatron Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container my-5">
        <header class="d-flex justify-content-between my-4">
            <h1>Welcome, <?php echo htmlspecialchars($user_name); ?>!</h1>
            <a href="logout.php" class="btn btn-danger">Logout</a>
        </header>

        <div class="row">
            <div class="col-md-6">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Plants List</h5>
                        <p class="card-text">View and manage all your plants.</p>
                        <a href="plant_list.php" class="btn btn-primary">Go to Plants List</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">To-Do List</h5>
                        <p class="card-text">Manage your plant care tasks and routines.</p>
                        <a href="view_tasks.php" class="btn btn-primary">Go to To-Do List</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
