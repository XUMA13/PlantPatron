<?php
session_start();
include 'db.php';

// Redirect to login page if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id']; // Get the logged-in user's ID

// Fetch plants associated with the logged-in user
$stmt = $conn->prepare("SELECT * FROM plants WHERE OWNER_ID = ?");
$stmt->bind_param('i', $user_id);
$stmt->execute();
$plants = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Plants</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container my-5">
        <h1>Your Plants</h1>
        <p>Welcome, <?= $_SESSION['name']; ?>!</p>
        <a href="logout.php" class="btn btn-danger mb-3">Logout</a>

        <a href="add_plant.php" class="btn btn-success mb-3">Add New Plant</a>

        <?php if ($plants->num_rows > 0): ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Plant Name</th>
                        <th>Category</th>
                        <th>Location</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($plant = $plants->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($plant['NAME']); ?></td>
                            <td><?= htmlspecialchars($plant['CATEGORY']); ?></td>
                            <td><?= htmlspecialchars($plant['LOCATION']); ?></td>
                            <td>
                                <a href="delete_plant.php?id=<?= $plant['PLANT_ID']; ?>" class="btn btn-danger">Delete</a>
                                <a href="view_plant.php?id=<?= $plant['PLANT_ID']; ?>" class="btn btn-primary">View</a>
                                <a href="progress.php?plant_id=<?= $plant['PLANT_ID']; ?>" class="btn btn-info">Progress</a>
                                <a href="health_condition.php?plant_id=<?= $plant['PLANT_ID']; ?>" class="btn btn-info">Health</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No plants found. Add a new plant!</p>
        <?php endif; ?>
    </div>
</body>
</html>
