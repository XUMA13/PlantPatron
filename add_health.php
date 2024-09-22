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
    echo "Error: Plant ID not provided.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $disease = $_POST['disease'];
    $treatment_status = $_POST['treatment_status'];
    $ongoing_medication = $_POST['ongoing_medication'];

    // Insert the health condition into the database
    $stmt = $conn->prepare("INSERT INTO HEALTH_CONDITION (PLANT_ID, DISEASE, TREATMENT_STATUS, ONGOING_MEDICATION) VALUES (?, ?, ?, ?)");
    $stmt->bind_param('isss', $plant_id, $disease, $treatment_status, $ongoing_medication);

    if ($stmt->execute()) {
        echo "Health condition added successfully!";
        header("Location: health_condition.php?plant_id=" . $plant_id);
        exit();
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
    <title>Add Health Condition</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container my-5">
        <h1>Add Health Condition</h1>
        <form action="add_health.php?plant_id=<?= $plant_id; ?>" method="POST">
            <div class="mb-3">
                <label for="disease" class="form-label">Disease</label>
                <input type="text" class="form-control" id="disease" name="disease" required>
            </div>
            <div class="mb-3">
                <label for="treatment_status" class="form-label">Treatment Status</label>
                <input type="text" class="form-control" id="treatment_status" name="treatment_status" required>
            </div>
            <div class="mb-3">
                <label for="ongoing_medication" class="form-label">Ongoing Medication</label>
                <textarea class="form-control" id="ongoing_medication" name="ongoing_medication" required></textarea>
            </div>
            <button type="submit" class="btn btn-success">Submit</button>
        </form>
    </div>
</body>
</html>
