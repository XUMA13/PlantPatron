<?php
include 'db.php';
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $date_joined = date("Y-m-d H:i:s"); // Current date and time

    // Insert the user data into the `plant_owner` table
    $stmt = $conn->prepare("INSERT INTO plant_owner (NAME, EMAIL, PASSWORD, DATE_JOINED) VALUES (?, ?, ?, ?)");
    $stmt->bind_param('ssss', $name, $email, $password, $date_joined);

    if ($stmt->execute()) {
        $_SESSION['user_id'] = $stmt->insert_id; // Store user ID in session
        $_SESSION['name'] = $name; 
        $_SESSION['email'] = $email;
        $_SESSION['date_joined'] = $date_joined;
        header("Location: plant_list.php"); // Redirect to plant list page
        exit();
    } else {
        $error = "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container my-5">
        <h1>Sign Up</h1>
        <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
        <form action="signup.php" method="POST">
            <div class="form-group my-4">
                <label for="name">Name:</label>
                <input type="text" class="form-control" name="name" required>
            </div>
            <div class="form-group my-4">
                <label for="email">Email:</label>
                <input type="email" class="form-control" name="email" required>
            </div>
            <div class="form-group my-4">
                <label for="password">Password:</label>
                <input type="password" class="form-control" name="password" required>
            </div>
            <input type="submit" value="Sign Up" class="btn btn-primary">
        </form>
        <div class="mt-3">
            <a href="login.php">Already have an account? Login here</a>
        </div>
    </div>
</body>
</html>
