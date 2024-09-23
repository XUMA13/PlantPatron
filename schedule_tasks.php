<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$plant_id = $_GET['plant_id'] ?? null;

if (!$plant_id) {
    echo "Error: No plant selected.";
    exit();
}

// Fetch plant information
$plant_query = "SELECT * FROM PLANTS WHERE PLANT_ID = ? AND OWNER_ID = ?";
$stmt = $conn->prepare($plant_query);
$stmt->bind_param('ii', $plant_id, $user_id);
$stmt->execute();
$plant = $stmt->get_result()->fetch_assoc();

if (!$plant) {
    echo "Error: Plant not found.";
    exit();
}

// Handle Add Task
if (isset($_POST['add_task'])) {
    $task_name = $_POST['task_name'];
    $task_description = $_POST['task_description'];
    $task_date = $_POST['task_date'];
    $task_time = $_POST['task_time'];

    $add_task_query = "INSERT INTO TO_DO_LIST (ID, PLANT_ID, TASK_NAME, TASK_DESCRIPTION, TASK_DATE, TASK_TIME) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($add_task_query);
    $stmt->bind_param('iissss', $user_id, $plant_id, $task_name, $task_description, $task_date, $task_time);
    
    if ($stmt->execute()) {
        echo "Task added successfully!";
    } else {
        echo "Error adding task: " . $conn->error;
    }
}

// Handle Delete Task
if (isset($_POST['delete_task'])) {
    $task_id = $_POST['task_id'];
    
    $delete_task_query = "DELETE FROM TO_DO_LIST WHERE TASK_ID = ? AND ID = ? AND PLANT_ID = ?";
    $stmt = $conn->prepare($delete_task_query);
    $stmt->bind_param('iii', $task_id, $user_id, $plant_id);
    
    if ($stmt->execute()) {
        echo "Task deleted successfully!";
    } else {
        echo "Error deleting task: " . $conn->error;
    }
}

// Handle Edit Task
if (isset($_POST['edit_task'])) {
    $task_id = $_POST['task_id'];
    $task_name = $_POST['task_name'];
    $task_description = $_POST['task_description'];
    $task_date = $_POST['task_date'];
    $task_time = $_POST['task_time'];

    $edit_task_query = "UPDATE TO_DO_LIST SET TASK_NAME = ?, TASK_DESCRIPTION = ?, TASK_DATE = ?, TASK_TIME = ? WHERE TASK_ID = ? AND ID = ? AND PLANT_ID = ?";
    $stmt = $conn->prepare($edit_task_query);
    $stmt->bind_param('ssssiii', $task_name, $task_description, $task_date, $task_time, $task_id, $user_id, $plant_id);
    
    if ($stmt->execute()) {
        echo "Task updated successfully!";
    } else {
        echo "Error updating task: " . $conn->error;
    }
}

// Fetch tasks for the selected plant
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
    <title>Schedule Tasks</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container my-5">
        <h1>Schedule Tasks for <?= htmlspecialchars($plant['NAME']); ?></h1>
        <a href="task_list.php" class="btn btn-secondary mb-3">Back to Plants</a>

        <!-- Add Task Form -->
        <form method="post" class="mb-5">
            <h3>Add New Task</h3>
            <div class="mb-3">
                <label for="task_name" class="form-label">Task Name</label>
                <input type="text" name="task_name" id="task_name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="task_description" class="form-label">Task Description</label>
                <textarea name="task_description" id="task_description" class="form-control" rows="3" required></textarea>
            </div>
            <div class="mb-3">
                <label for="task_date" class="form-label">Task Date</label>
                <input type="date" name="task_date" id="task_date" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="task_time" class="form-label">Task Time</label>
                <input type="time" name="task_time" id="task_time" class="form-control" required>
            </div>
            <button type="submit" name="add_task" class="btn btn-primary">Add Task</button>
        </form>

        <!-- Task List -->
        <h3>Existing Tasks</h3>
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
                            <td><?= $task['DONE_TASKS'] ? 'Completed' : 'Pending'; ?></td>
                            <td>
                                <!-- Edit Task Form (within the same table row) -->
                                <form method="post" style="display:inline-block;">
                                    <input type="hidden" name="task_id" value="<?= $task['TASK_ID']; ?>">
                                    <input type="text" name="task_name" value="<?= htmlspecialchars($task['TASK_NAME']); ?>" required>
                                    <input type="text" name="task_description" value="<?= htmlspecialchars($task['TASK_DESCRIPTION']); ?>" required>
                                    <input type="date" name="task_date" value="<?= $task['TASK_DATE']; ?>" required>
                                    <input type="time" name="task_time" value="<?= $task['TASK_TIME']; ?>" required>
                                    <button type="submit" name="edit_task" class="btn btn-warning">Edit</button>
                                </form>

                                <!-- Delete Task Button -->
                                <form method="post" style="display:inline-block;">
                                    <input type="hidden" name="task_id" value="<?= $task['TASK_ID']; ?>">
                                    <button type="submit" name="delete_task" class="btn btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No tasks found for this plant.</p>
        <?php endif; ?>

        

    


        <script>
            // Function to trigger a notification
            function notifyUser(taskName) {
                if (!("Notification" in window)) {
                    alert("This browser does not support desktop notifications.");
                } else if (Notification.permission === "granted") {
                    new Notification("Task Reminder", {
                        body: "It's time to complete the task: " + taskName
                    });
                } else if (Notification.permission !== "denied") {
                    Notification.requestPermission().then(function(permission) {
                        if (permission === "granted") {
                            new Notification("Task Reminder", {
                                body: "It's time to complete the task: " + taskName
                            });
                        }
                    });
                }
            }

            // Function to check tasks periodically
            function checkDueTasks() {
                const currentTime = new Date();
                console.log("Current Time:", currentTime); // Log current time

                fetch('get_due_tasks.php')
                    .then(response => response.json())
                    .then(tasks => {
                        console.log("Fetched tasks:", tasks); // Log fetched tasks
                        tasks.forEach(task => {
                            const taskTime = new Date(task.TASK_DATE + 'T' + task.TASK_TIME);
                            console.log(`Task: ${task.TASK_NAME}, Due Time: ${taskTime}, Done: ${task.DONE_TASKS}`); // Log each task

                            // Check if the task is due within 5 minutes
                            const dueTime = new Date(taskTime.getTime() - (5 * 60 * 1000)); // 5 minutes before due time
                            if (dueTime <= currentTime && task.DONE_TASKS == 0) {
                                notifyUser(task.TASK_NAME);
                            }
                        });
                    })
                    .catch(error => console.error('Error fetching due tasks:', error));
            }

            // Request notification permission on page load
            if (Notification.permission === "default") {
                Notification.requestPermission();
            }

            

            // Check for due tasks every minute
            setInterval(checkDueTasks, 60000); // 60000 ms = 1 minute

            // Optional: Check immediately on page load
            checkDueTasks();
        </script>



    </div>
</body>
</html>
