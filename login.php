<?php
include 'db.php';
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Retrieve user from the `plant_owner` table by email
    $stmt = $conn->prepare("SELECT ID, NAME, PASSWORD, DATE_JOINED FROM plant_owner WHERE EMAIL = ?");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Assuming plain text passwords (but you should use password hashing)
        if ($user['PASSWORD'] === $password) {
            $_SESSION['user_id'] = $user['ID'];
            $_SESSION['name'] = $user['NAME'];
            $_SESSION['date_joined'] = $user['DATE_JOINED'];
            header("Location: plant_list.php"); // Redirect to plant list
            exit();
        } else {
            $error = "Incorrect password!";
        }
    } else {
        $error = "No user found with that email!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container my-5">
        <h1>Login</h1>
        <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
        <form action="login.php" method="POST">
            <div class="form-group my-4">
                <label for="email">Email:</label>
                <input type="email" class="form-control" name="email" required>
            </div>
            <div class="form-group my-4">
                <label for="password">Password:</label>
                <input type="password" class="form-control" name="password" required>
            </div>
            <input type="submit" value="Login" class="btn btn-primary">
        </form>
        <div class="mt-3">
            <a href="signup.php">Don't have an account? Sign Up</a>
        </div>
    </div>
</body>
</html>
