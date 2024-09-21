<?php
include 'db.php';
session_start();

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $plant_id = $_GET['id'];
    $user_id = $_SESSION['user_id'];

    // Ensure that the plant belongs to the logged-in user
    $stmt = $conn->prepare("DELETE FROM plants WHERE PLANT_ID = ? AND OWNER_ID = ?");
    $stmt->bind_param('ii', $plant_id, $user_id);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Plant deleted successfully!";
        header("Location: plant_list.php");
    } else {
        echo "Error deleting plant: " . $stmt->error;
    }
} else {
    echo "Invalid request.";
}
?>
