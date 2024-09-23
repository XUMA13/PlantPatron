<?php
session_start();
include 'db.php';

// Redirect to login page if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id']; // Get the logged-in user's ID

// Fetch plants for the logged-in user
$plant_query = "SELECT * FROM PLANTS WHERE OWNER_ID = ?";
$stmt = $conn->prepare($plant_query);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$plants = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task List</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container my-5">
        <h1>Your Plants</h1>
        <a href="index.php" class="btn btn-secondary mb-3">Back to Homepage</a>
        
        <!-- Display Plant List -->
        <?php if ($plants->num_rows > 0): ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Plant Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($plant = $plants->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($plant['NAME']); ?></td>
                            <td>
                                <a href="view_tasks.php?plant_id=<?= $plant['PLANT_ID'] ?>" class="btn btn-info">View Tasks</a>
                                <a href="schedule_tasks.php?plant_id=<?= $plant['PLANT_ID'] ?>" class="btn btn-primary">Schedule Task</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No plants found. Add a new plant to get started!</p>
        <?php endif; ?>
    </div>
</body>
</html>
