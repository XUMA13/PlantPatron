<?php
include 'db.php';
session_start();

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $plant_id = $_GET['id'];
    $user_id = $_SESSION['user_id']; 

    // Ensure that the plant belongs to the logged-in user
    $stmt = $conn->prepare("SELECT PLANT_ID FROM plants WHERE PLANT_ID = ? AND OWNER_ID = ?");
    $stmt->bind_param('ii', $plant_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Begin transaction
        $conn->begin_transaction();

        try {
            // Delete tasks related to the plant (due and done tasks)
            $stmt = $conn->prepare("DELETE FROM TO_DO_LIST WHERE PLANT_ID = ?");
            $stmt->bind_param('i', $plant_id);
            $stmt->execute();

            // Delete from health_condition table
            $stmt = $conn->prepare("DELETE FROM health_condition WHERE PLANT_ID = ?");
            $stmt->bind_param('i', $plant_id);
            $stmt->execute();

            // Delete from plant_photos table 
            $stmt = $conn->prepare("DELETE FROM plant_photos WHERE PLANT_ID = ?");
            $stmt->bind_param('i', $plant_id);
            $stmt->execute();

            // Finally, delete from plants table
            $stmt = $conn->prepare("DELETE FROM plants WHERE PLANT_ID = ? AND OWNER_ID = ?");
            $stmt->bind_param('ii', $plant_id, $user_id);

            if ($stmt->execute()) {
                // Commit transaction
                $conn->commit();

                // Set success message and redirect
                $_SESSION['message'] = "Plant and its related tasks deleted successfully!";
                header("Location: plant_list.php");
            } else {
                throw new Exception("Error deleting plant: " . $stmt->error);
            }
        } catch (Exception $e) {
            // Rollback transaction on failure
            $conn->rollback();
            echo $e->getMessage();
        }
    } else {
        echo "Plant not found or you do not have permission to delete this plant.";
    }
} else {
    echo "Invalid request.";
}
?>
