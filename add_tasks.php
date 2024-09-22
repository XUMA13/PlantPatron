<?php
include 'db.php';
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

// Fetch plants for the logged-in user
$stmt = $conn->prepare("SELECT PLANT_ID, NAME FROM plants WHERE OWNER_ID = ?");
$stmt->bind_param('i', $user_id);
$stmt->execute();
$plants = $stmt->get_result();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $plant_id = $_POST['plant_id'];
    $task_name = $_POST['task_name'];
    $task_description = $_POST['task_description'];
    $task_date = $_POST['task_date'];
    $task_time = $_POST['task_time'];

    $stmt = $conn->prepare("INSERT INTO TO_DO_LIST (ID, PLANT_ID, TASK_NAME, TASK_DESCRIPTION, TASK_DATE, TASK_TIME) 
        VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param('iissss', $user_id, $plant_id, $task_name, $task_description, $task_date, $task_time);

    if ($stmt->execute()) {
        echo "Task added successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Task</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container my-5">
        <header class="d-flex justify-content-between my-4">
            <h1>Add New Task</h1>
            <div>
                <a href="view_tasks.php" class="btn btn-primary">View Tasks</a>
            </div>
        </header>

        <form action="add_task.php" method="POST">
            <div class="form-group my-4">
                <label for="plant_id">Select Plant:</label>
                <select class="form-control" name="plant_id" required>
                    <option value="">Choose a plant...</option>
                    <?php while ($plant = $plants->fetch_assoc()): ?>
                        <option value="<?php echo $plant['PLANT_ID']; ?>"><?php echo htmlspecialchars($plant['NAME']); ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group my-4">
                <label for="task_name">Task Name:</label>
                <input type="text" class="form-control" name="task_name" required>
            </div>
            <div class="form-group my-4">
                <label for="task_description">Task Description:</label>
                <textarea class="form-control" name="task_description" required></textarea>
            </div>
            <div class="form-group my-4">
                <label for="task_date">Task Date:</label>
                <input type="date" class="form-control" name="task_date" required>
            </div>
            <div class="form-group my-4">
                <label for="task_time">Task Time:</label>
                <input type="time" class="form-control" name="task_time" required>
            </div>
            <div class="form-group my-4">
                <input type="submit" value="Add Task" class="btn btn-primary">
            </div>
        </form>
    </div>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
