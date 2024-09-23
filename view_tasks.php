<?php
session_start();
include 'db.php';

// Redirect if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id']; // Logged-in user's ID

// Fetch plants for the user
$plant_id = $_GET['plant_id'] ?? null;

if (!$plant_id) {
    echo "Error: No plant selected.";
    exit();
}

// Fetch plant information
$stmt = $conn->prepare("SELECT * FROM PLANTS WHERE PLANT_ID = ? AND OWNER_ID = ?");
$stmt->bind_param('ii', $plant_id, $user_id);
$stmt->execute();
$plant = $stmt->get_result()->fetch_assoc();

if (!$plant) {
    echo "Error: Plant not found.";
    exit();
}

// Handle Status Toggle (Done/Due)
if (isset($_POST['toggle_status'])) {
    $task_id = $_POST['task_id'];
    $current_status = $_POST['current_status']; // Get current status (due or done)

    // Toggle status: if current status is due, mark as done; else, mark as due
    $new_done_status = $current_status == 'due' ? 1 : 0;
    $new_due_status = !$new_done_status;

    $update_status_query = "UPDATE TO_DO_LIST SET DONE_TASKS = ?, DUE_TASKS = ? WHERE TASK_ID = ? AND PLANT_ID = ? AND ID = ?";
    $stmt = $conn->prepare($update_status_query);
    $stmt->bind_param('iiiii', $new_done_status, $new_due_status, $task_id, $plant_id, $user_id);

    if ($stmt->execute()) {
        echo "Task status updated!";
    } else {
        echo "Error updating status: " . $conn->error;
    }
}

// Fetch tasks for the plant
$task_query = "SELECT * FROM TO_DO_LIST WHERE PLANT_ID = ? AND ID = ? ORDER BY TASK_DATE, TASK_TIME";
$stmt = $conn->prepare($task_query);
$stmt->bind_param('ii', $plant_id, $user_id);
$stmt->execute();
$tasks = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Tasks for <?= htmlspecialchars($plant['NAME']); ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container my-5">
        <h1>Tasks for <?= htmlspecialchars($plant['NAME']); ?></h1>
        <a href="plant_list.php" class="btn btn-secondary mb-3">Back to Plant List</a>

        <h3>Task List</h3>
        <?php if ($tasks->num_rows > 0): ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Task Name</th>
                        <th>Description</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($task = $tasks->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($task['TASK_NAME']); ?></td>
                            <td><?= htmlspecialchars($task['TASK_DESCRIPTION']); ?></td>
                            <td><?= htmlspecialchars($task['TASK_DATE']); ?></td>
                            <td><?= htmlspecialchars($task['TASK_TIME']); ?></td>
                            <td><?= $task['DONE_TASKS'] ? 'Done' : 'Pending'; ?></td>
                            <td>
                                <!-- Toggle Status Form -->
                                <form method="post" style="display:inline-block;">
                                    <input type="hidden" name="task_id" value="<?= $task['TASK_ID']; ?>">
                                    <input type="hidden" name="current_status" value="<?= $task['DONE_TASKS'] ? 'done' : 'due'; ?>">
                                    <button type="submit" name="toggle_status" class="btn btn-<?= $task['DONE_TASKS'] ? 'success' : 'warning'; ?>">
                                        <?= $task['DONE_TASKS'] ? 'Mark as Due' : 'Mark as Done'; ?>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No tasks found for this plant.</p>
        <?php endif; ?>
    </div>
</body>
</html>
