<?php
// Include database connection
include 'db.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Get plant data from form submission
    $plant_id = $_POST['plant_id'];
    $name = $_POST['name'];
    $category = $_POST['category'];
    $sunlight = $_POST['sunlight'];
    $pot_size = $_POST['pot_size'];
    $fertilizer = $_POST['fertilizer'];
    $leaf_condition = $_POST['leaf_condition'];
    $watering_status = $_POST['watering_status'];
    $expected_life_span = $_POST['expected_life_span'];
    $age = $_POST['age'];
    $location = $_POST['location'];
    $species = $_POST['species'];

    // Update query
    $query = "UPDATE plants 
              SET name='$name', category='$category', sunlight='$sunlight', pot_size='$pot_size', fertilizer='$fertilizer', leaf_condition='$leaf_condition', watering_status='$watering_status', expected_life_span='$expected_life_span', age='$age', location='$location', species='$species'
              WHERE plant_id = $plant_id";

    // Execute query
    if (mysqli_query($conn, $query)) {
        echo "Plant updated successfully.";
        header("Location: plant_list.php");  // Redirect to the plant list page
        exit();
    } else {
        echo "Error updating plant: " . mysqli_error($conn);
    }
} else {
    echo "Invalid request!";
}
?>
