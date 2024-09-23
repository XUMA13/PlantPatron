<?php
session_start();
include 'db.php';

$user_id = $_SESSION['user_id'];

// Get tasks that are scheduled but not marked as done
$query = "SELECT * FROM TO_DO_LIST WHERE ID = ? AND DONE_TASKS = 0 ORDER BY TASK_DATE, TASK_TIME";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();

$tasks = [];
while ($row = $result->fetch_assoc()) {
    $tasks[] = $row;
}

// Return tasks in JSON format
header('Content-Type: application/json');
echo json_encode($tasks);
?>
