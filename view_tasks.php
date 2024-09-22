<?php
include 'db.php';  // Including database connection
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");  // Redirect to login page if not logged in
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch all tasks for the logged-in user
$stmt = $conn->prepare("SELECT TASK_ID, PLANT_ID, TASK_NAME, TASK_DESCRIPTION, TASK_DATE, TASK_TIME, DONE_TASKS, DUE_TASKS FROM TO_DO_LIST WHERE ID = ?");
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Tasks</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
</head>
<body>
    <div class="container my-5">
        <header class="d-flex justify-content-between my-4">
            <h1>Your Tasks</h1>
            <a href="schedule_task.php" class="btn btn-primary">Schedule New Task</a>
        </header>

        <?php if ($result->num_rows > 0): ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Task Name</th>
                        <th>Description</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Completed</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['TASK_NAME']); ?></td>
                            <td><?php echo htmlspecialchars($row['TASK_DESCRIPTION']); ?></td>
                            <td><?php echo htmlspecialchars($row['TASK_DATE']); ?></td>
                            <td><?php echo htmlspecialchars($row['TASK_TIME']); ?></td>
                            <td><?php echo $row['DONE_TASKS'] ? 'Yes' : 'No'; ?></td>
                            <td>
                                <!-- Edit button -->
                                <a href="edit_task.php?task_id=<?php echo $row['TASK_ID']; ?>" class="btn btn-warning">Edit</a>

                                <!-- Delete button -->
                                <a href="delete_task.php?task_id=<?php echo $row['TASK_ID']; ?>" class="btn btn-danger" 
                                   onclick="return confirm('Are you sure you want to delete this task?');">
                                    Delete
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No tasks found. <a href="schedule_task.php">Schedule a task</a>.</p>
        <?php endif; ?>

        <?php $stmt->close(); ?>
        <?php $conn->close(); ?>
    </div>
</body>
</html>
