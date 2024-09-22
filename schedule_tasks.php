<?php
// Include the database connection file
include 'db.php';
session_start(); // Start the session

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form input
    $plant_id = $_POST['plant_id'];
    $task_name = $_POST['task_name'];
    $task_description = $_POST['task_description'];
    $task_date = $_POST['task_date'];
    $task_time = $_POST['task_time'];
    $user_id = $_SESSION['user_id']; // Assuming user ID is stored in the session

    // Insert task into the TO_DO_LIST table
    $sql = "INSERT INTO TO_DO_LIST (ID, PLANT_ID, TASK_TIME, TASK_DATE, TASK_NAME, TASK_DESCRIPTION, DONE_TASKS, DUE_TASKS)
            VALUES (?, ?, ?, ?, ?, ?, false, true)";

    // Prepare and execute the SQL statement
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("iissss", $user_id, $plant_id, $task_time, $task_date, $task_name, $task_description);
        
        if ($stmt->execute()) {
            echo "Task scheduled successfully!";
        } else {
            echo "Error scheduling task: " . $stmt->error;
        }

        $stmt->close(); // Close the statement
    } else {
        echo "Error preparing statement: " . $conn->error;
    }

    $conn->close(); // Close the database connection
}
?>

<!-- HTML form for scheduling a task -->
<form action="schedule_tasks.php" method="POST">
    <label for="plant_id">Select Plant:</label>
    <select name="plant_id" id="plant_id">
        <?php
        // Fetch plants owned by the logged-in user
        $result = $conn->query("SELECT PLANT_ID, PLANT_NAME FROM PLANTS WHERE OWNER_ID = {$_SESSION['user_id']}");
        while ($row = $result->fetch_assoc()) {
            echo "<option value='{$row['PLANT_ID']}'>{$row['PLANT_NAME']}</option>";
        }
        ?>
    </select>

    <label for="task_name">Task Name:</label>
    <input type="text" name="task_name" id="task_name" required>

    <label for="task_description">Task Description:</label>
    <textarea name="task_description" id="task_description"></textarea>

    <label for="task_date">Task Date:</label>
    <input type="date" name="task_date" id="task_date" required>

    <label for="task_time">Task Time:</label>
    <input type="time" name="task_time" id="task_time" required>

    <button type="submit">Schedule Task</button>
</form>
