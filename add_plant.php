<?php
include 'db.php';
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
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
    $owner_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("INSERT INTO plants 
        (OWNER_ID, NAME, CATEGORY, SUNLIGHT_INFO, POT_SIZE, FERTILIZER, LEAF_CONDITION, WATERING_STATUS, EXPECTED_LIFE_SPAN, AGE, LOCATION, SPECIES) 
        VALUES 
        (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $stmt->bind_param('isssssssssss', $owner_id, $name, $category, $sunlight, $pot_size, $fertilizer, $leaf_condition, $watering_status, $expected_life_span, $age, $location, $species);

    if ($stmt->execute()) {
        echo "Plant added successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Plant</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
</head>
<body>
    <div class="container my-5">
        <header class="d-flex justify-content-between my-4">
            <h1>Add New Plant</h1>
            <div>
                <a href="plant_list.php" class="btn btn-primary">Back to Plant List</a>
            </div>
        </header>

<form action="add_plant.php" method="POST">
    <div class="form-group my-4">
        <label for="name">Plant Name:</label>
        <small class="form-text text-muted">The name you want to assign to this plant.</small>
        <input type="text" class="form-control" name="name" required>
    </div>

    <div class="form-group my-4">
        <label for="category">Category:</label>
        <small class="form-text text-muted">The type or category of the plant (e.g., Succulent, Herb).</small>
        <select class="form-control" name="category" required>
            <option value="">Select Category</option>
            <option value="Succulent">Succulent</option>
            <option value="Flowering">Flowering</option>
            <option value="Foliage">Foliage</option>
            <option value="Herb">Herb</option>
            <option value="Tree">Tree</option>
            <option value="Shrub">Shrub</option>
            <option value="Fern">Fern</option>
            <option value="Aquatic">Aquatic</option>
            <option value="Cacti">Cacti</option>
            <option value="Bonsai">Bonsai</option>
        </select>
    </div>

    <div class="form-group my-4">
        <label for="sunlight">Sunlight Info:</label>
        <small class="form-text text-muted">Required sunlight level for the plant (e.g., Full Sun, Partial Sun).</small>
        <select class="form-control" name="sunlight" required>
            <option value="">Select Sunlight Info</option>
            <option value="Full Sun">Full Sun</option>
            <option value="Partial Sun">Partial Sun</option>
            <option value="Full Shade">Full Shade</option>
            <option value="Bright Indirect Light">Bright Indirect Light</option>
            <option value="Low Light">Low Light</option>
        </select>
    </div>

    <div class="form-group my-4">
        <label for="pot_size">Pot Size:</label>
        <small class="form-text text-muted">The size of the pot the plant is in (e.g., Small, Medium).</small>
        <select class="form-control" name="pot_size" required>
            <option value="">Select Pot Size</option>
            <option value="Small (under 6 inches)">Small (under 6 inches)</option>
            <option value="Medium (6-12 inches)">Medium (6-12 inches)</option>
            <option value="Large (12-18 inches)">Large (12-18 inches)</option>
            <option value="Extra Large (over 18 inches)">Extra Large (over 18 inches)</option>
        </select>
    </div>

    <div class="form-group my-4">
        <label for="fertilizer">Fertilizer:</label>
        <small class="form-text text-muted">Recommended fertilizer for this plant (e.g., Organic, Slow Release).</small>
        <select class="form-control" name="fertilizer" required>
            <option value="">Select Fertilizer</option>
            <option value="Organic">Organic</option>
            <option value="Slow Release">Slow Release</option>
            <option value="Water-Soluble">Water-Soluble</option>
            <option value="Liquid Fertilizer">Liquid Fertilizer</option>
            <option value="Granular">Granular</option>
            <option value="Fertilizer Spikes">Fertilizer Spikes</option>
            <option value="None">None</option>
        </select>
    </div>

    <div class="form-group my-4">
        <label for="leaf_condition">Leaf Condition:</label>
        <small class="form-text text-muted">The current health of the plant's leaves (e.g., Healthy, Wilting).</small>
        <select class="form-control" name="leaf_condition" required>
            <option value="">Select Leaf Condition</option>
            <option value="Healthy">Healthy</option>
            <option value="Wilting">Wilting</option>
            <option value="Yellowing">Yellowing</option>
            <option value="Dry/Brown">Dry/Brown</option>
            <option value="Spotting">Spotting</option>
            <option value="New Growth">New Growth</option>
        </select>
    </div>

    <div class="form-group my-4">
        <label for="watering_status">Watering Status:</label>
        <small class="form-text text-muted">The current water needs of the plant (e.g., Overwatered, Well-Hydrated).</small>
        <select class="form-control" name="watering_status" required>
            <option value="">Select Watering Status</option>
            <option value="Overwatered">Overwatered</option>
            <option value="Underwatered">Underwatered</option>
            <option value="Needs Water">Needs Water</option>
            <option value="Recently Watered">Recently Watered</option>
            <option value="Well-Hydrated">Well-Hydrated</option>
        </select>
    </div>

    <div class="form-group my-4">
        <label for="expected_life_span">Expected Life Span:</label>
        <small class="form-text text-muted">The typical lifespan of this plant (e.g., Annual, Perennial).</small>
        <select class="form-control" name="expected_life_span" required>
            <option value="">Select Expected Life Span</option>
            <option value="Annual (1 year)">Annual (1 year)</option>
            <option value="Biennial (2 years)">Biennial (2 years)</option>
            <option value="Perennial (3+ years)">Perennial (3+ years)</option>
        </select>
    </div>

    <div class="form-group my-4">
        <label for="age">Age of Plant:</label>
        <small class="form-text text-muted">The current age of the plant (e.g., 2 months, 1 year).</small>
        <input type="text" class="form-control" name="age" required>
    </div>

    <div class="form-group my-4">
        <label for="location">Location:</label>
        <small class="form-text text-muted">The place where the plant is located (e.g., Indoor - Living Room).</small>
        <select class="form-control" name="location" required>
            <option value="">Select Location</option>
            <option value="Indoor - Living Room">Indoor - Living Room</option>
            <option value="Indoor - Bedroom">Indoor - Bedroom</option>
            <option value="Indoor - Kitchen">Indoor - Kitchen</option>
            <option value="Indoor - Office">Indoor - Office</option>
            <option value="Indoor - Bathroom">Indoor - Bathroom</option>
            <option value="Indoor - Hallway">Indoor - Hallway</option>
            <option value="Outdoor - Frontyard">Outdoor - Frontyard</option>
            <option value="Outdoor - Vegetable Garden Bed">Outdoor - Vegetable Garden Bed</option>
            <option value="Outdoor - Flower Bed">Outdoor - Flower Bed</option>
            <option value="Outdoor - Garden">Outdoor - Garden</option>
            <option value="Outdoor - Backyard">Outdoor - Backyard</option>
            <option value="Outdoor - Patio">Outdoor - Patio</option>
            <option value="Outdoor - Porch">Outdoor - Porch</option>
            <option value="Outdoor - Balcony">Outdoor - Balcony</option>
            <option value="Outdoor - Terrace">Outdoor - Terrace</option>
        </select>
    </div>

    <div class="form-group my-4">
        <label for="species">Species:</label>
        <small class="form-text text-muted">The species or scientific name of the plant (e.g., Monstera deliciosa).</small>
        <input type="text" class="form-control" name="species" required>
    </div>

    <div class="form-group my-4">
        <input type="submit" value="Add Plant" class="btn btn-primary">
    </div>
</form>

