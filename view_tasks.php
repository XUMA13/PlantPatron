<?php
include 'db.php';
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch tasks from the TO_DO_LIST table
$stmt = $conn->prepare("SELECT * FROM TO_DO_LIST WHERE ID = ?");
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Scheduled Tasks</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container my-5">
        <header class="d-flex justify-content-between my-4">
            <h1>Scheduled Tasks</h1>
            <div>
                <a href="add_tasks.php" class="btn btn-primary">Add New Task</a>
                <a href="index.php" class="btn btn-secondary">Back to Dashboard</a>
            </div>
        </header>

        <table class="table">
            <thead>
                <tr>
                    <th>Task ID</th>
                    <th>Plant ID</th>
                    <th>Task Name</th>
                    <th>Task Description</th>
                    <th>Task Date</th>
                    <th>Task Time</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($task = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $task['TASK_ID']; ?></td>
                    <td><?php echo $task['PLANT_ID']; ?></td>
                    <td><?php echo $task['TASK_NAME']; ?></td>
                    <td><?php echo $task['TASK_DESCRIPTION']; ?></td>
                    <td><?php echo $task['TASK_DATE']; ?></td>
                    <td><?php echo $task['TASK_TIME']; ?></td>
                    <td><?php echo $task['DONE_TASKS'] ? 'Completed' : 'Pending'; ?></td>
                    <td>
                        <a href="edit_tasks.php?task_id=<?php echo $task['TASK_ID']; ?>" class="btn btn-warning btn-sm">Edit</a>
                        <a href="delete_tasks.php?task_id=<?php echo $task['TASK_ID']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this task?');">Delete</a>
                        <form action="mark_done.php" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to mark this task as done?');">
                            <input type="hidden" name="task_id" value="<?php echo $task['TASK_ID']; ?>">
                            <button type="submit" class="btn btn-success btn-sm" <?php echo $task['DONE_TASKS'] ? 'disabled' : ''; ?>>✔️</button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
