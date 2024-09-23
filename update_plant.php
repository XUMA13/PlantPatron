<?php
include 'db.php';
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch plant details if an ID is specified
if (isset($_GET['plant_id'])) {
    $plant_id = $_GET['plant_id'];
    $user_id = $_SESSION['user_id'];

    // Fetch the plant details
    $stmt = $conn->prepare("SELECT * FROM plants WHERE PLANT_ID = ? AND OWNER_ID = ?");
    $stmt->bind_param('ii', $plant_id, $user_id);
    $stmt->execute();
    $plant = $stmt->get_result()->fetch_assoc();

    if (!$plant) {
        echo "Plant not found or you don't have permission to update this plant.";
        exit();
    }
} else {
    echo "Invalid request.";
    exit();
}

// Handle form submission
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

    // Update the plant details
    $stmt = $conn->prepare("UPDATE plants SET NAME=?, CATEGORY=?, SUNLIGHT_INFO=?, POT_SIZE=?, FERTILIZER=?, LEAF_CONDITION=?, WATERING_STATUS=?, EXPECTED_LIFE_SPAN=?, AGE=?, LOCATION=?, SPECIES=? WHERE PLANT_ID=? AND OWNER_ID=?");
    $stmt->bind_param('ssssssssssssi', $name, $category, $sunlight, $pot_size, $fertilizer, $leaf_condition, $watering_status, $expected_life_span, $age, $location, $species, $plant_id, $user_id);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Plant updated successfully!";
        header("Location: plant_list.php");
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
    <title>Update Plant</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container my-5">
        <h1>Update Plant: <?= htmlspecialchars($plant['NAME']); ?></h1>
        <form action="update_plant.php?plant_id=<?= $plant_id; ?>" method="POST">
            <div class="form-group my-4">
                <label for="name">Plant Name:</label>
                <input type="text" class="form-control" name="name" value="<?= htmlspecialchars($plant['NAME']); ?>" required>
            </div>

            <div class="form-group my-4">
                <label for="category">Category:</label>
                <select class="form-control" name="category" required>
                    <option value="">Select Category</option>
                    <option value="Succulent" <?= $plant['CATEGORY'] === 'Succulent' ? 'selected' : ''; ?>>Succulent</option>
                    <option value="Flowering" <?= $plant['CATEGORY'] === 'Flowering' ? 'selected' : ''; ?>>Flowering</option>
                    <option value="Foliage" <?= $plant['CATEGORY'] === 'Foliage' ? 'selected' : ''; ?>>Foliage</option>
                    <option value="Herb" <?= $plant['CATEGORY'] === 'Herb' ? 'selected' : ''; ?>>Herb</option>
                    <option value="Tree" <?= $plant['CATEGORY'] === 'Tree' ? 'selected' : ''; ?>>Tree</option>
                    <option value="Shrub" <?= $plant['CATEGORY'] === 'Shrub' ? 'selected' : ''; ?>>Shrub</option>
                    <option value="Fern" <?= $plant['CATEGORY'] === 'Fern' ? 'selected' : ''; ?>>Fern</option>
                    <option value="Aquatic" <?= $plant['CATEGORY'] === 'Aquatic' ? 'selected' : ''; ?>>Aquatic</option>
                    <option value="Cacti" <?= $plant['CATEGORY'] === 'Cacti' ? 'selected' : ''; ?>>Cacti</option>
                    <option value="Bonsai" <?= $plant['CATEGORY'] === 'Bonsai' ? 'selected' : ''; ?>>Bonsai</option>
                </select>
            </div>

            <div class="form-group my-4">
                <label for="sunlight">Sunlight Info:</label>
                <select class="form-control" name="sunlight" required>
                    <option value="">Select Sunlight Info</option>
                    <option value="Full Sun" <?= $plant['SUNLIGHT_INFO'] === 'Full Sun' ? 'selected' : ''; ?>>Full Sun</option>
                    <option value="Partial Sun" <?= $plant['SUNLIGHT_INFO'] === 'Partial Sun' ? 'selected' : ''; ?>>Partial Sun</option>
                    <option value="Full Shade" <?= $plant['SUNLIGHT_INFO'] === 'Full Shade' ? 'selected' : ''; ?>>Full Shade</option>
                    <option value="Bright Indirect Light" <?= $plant['SUNLIGHT_INFO'] === 'Bright Indirect Light' ? 'selected' : ''; ?>>Bright Indirect Light</option>
                    <option value="Low Light" <?= $plant['SUNLIGHT_INFO'] === 'Low Light' ? 'selected' : ''; ?>>Low Light</option>
                </select>
            </div>

            <div class="form-group my-4">
                <label for="pot_size">Pot Size:</label>
                <select class="form-control" name="pot_size" required>
                    <option value="">Select Pot Size</option>
                    <option value="Small (under 6 inches)" <?= $plant['POT_SIZE'] === 'Small (under 6 inches)' ? 'selected' : ''; ?>>Small (under 6 inches)</option>
                    <option value="Medium (6-12 inches)" <?= $plant['POT_SIZE'] === 'Medium (6-12 inches)' ? 'selected' : ''; ?>>Medium (6-12 inches)</option>
                    <option value="Large (12-18 inches)" <?= $plant['POT_SIZE'] === 'Large (12-18 inches)' ? 'selected' : ''; ?>>Large (12-18 inches)</option>
                    <option value="Extra Large (over 18 inches)" <?= $plant['POT_SIZE'] === 'Extra Large (over 18 inches)' ? 'selected' : ''; ?>>Extra Large (over 18 inches)</option>
                </select>
            </div>

            <div class="form-group my-4">
                <label for="fertilizer">Fertilizer:</label>
                <select class="form-control" name="fertilizer" required>
                    <option value="">Select Fertilizer</option>
                    <option value="Organic" <?= $plant['FERTILIZER'] === 'Organic' ? 'selected' : ''; ?>>Organic</option>
                    <option value="Slow Release" <?= $plant['FERTILIZER'] === 'Slow Release' ? 'selected' : ''; ?>>Slow Release</option>
                    <option value="Water-Soluble" <?= $plant['FERTILIZER'] === 'Water-Soluble' ? 'selected' : ''; ?>>Water-Soluble</option>
                    <option value="Liquid Fertilizer" <?= $plant['FERTILIZER'] === 'Liquid Fertilizer' ? 'selected' : ''; ?>>Liquid Fertilizer</option>
                    <option value="Granular" <?= $plant['FERTILIZER'] === 'Granular' ? 'selected' : ''; ?>>Granular</option>
                    <option value="Fertilizer Spikes" <?= $plant['FERTILIZER'] === 'Fertilizer Spikes' ? 'selected' : ''; ?>>Fertilizer Spikes</option>
                    <option value="None" <?= $plant['FERTILIZER'] === 'None' ? 'selected' : ''; ?>>None</option>
                </select>
            </div>

            <div class="form-group my-4">
                <label for="leaf_condition">Leaf Condition:</label>
                <select class="form-control" name="leaf_condition" required>
                    <option value="">Select Leaf Condition</option>
                    <option value="Healthy" <?= $plant['LEAF_CONDITION'] === 'Healthy' ? 'selected' : ''; ?>>Healthy</option>
                    <option value="Wilting" <?= $plant['LEAF_CONDITION'] === 'Wilting' ? 'selected' : ''; ?>>Wilting</option>
                    <option value="Yellowing" <?= $plant['LEAF_CONDITION'] === 'Yellowing' ? 'selected' : ''; ?>>Yellowing</option>
                    <option value="Dry/Brown" <?= $plant['LEAF_CONDITION'] === 'Dry/Brown' ? 'selected' : ''; ?>>Dry/Brown</option>
                    <option value="Spotting" <?= $plant['LEAF_CONDITION'] === 'Spotting' ? 'selected' : ''; ?>>Spotting</option>
                    <option value="New Growth" <?= $plant['LEAF_CONDITION'] === 'New Growth' ? 'selected' : ''; ?>>New Growth</option>
                </select>
            </div>

            <div class="form-group my-4">
                <label for="watering_status">Watering Status:</label>
                <select class="form-control" name="watering_status" required>
                    <option value="">Select Watering Status</option>
                    <option value="Overwatered" <?= $plant['WATERING_STATUS'] === 'Overwatered' ? 'selected' : ''; ?>>Overwatered</option>
                    <option value="Underwatered" <?= $plant['WATERING_STATUS'] === 'Underwatered' ? 'selected' : ''; ?>>Underwatered</option>
                    <option value="Needs Water" <?= $plant['WATERING_STATUS'] === 'Needs Water' ? 'selected' : ''; ?>>Needs Water</option>
                    <option value="Recently Watered" <?= $plant['WATERING_STATUS'] === 'Recently Watered' ? 'selected' : ''; ?>>Recently Watered</option>
                    <option value="Well-Hydrated" <?= $plant['WATERING_STATUS'] === 'Well-Hydrated' ? 'selected' : ''; ?>>Well-Hydrated</option>
                </select>
            </div>

            <div class="form-group my-4">
                <label for="expected_life_span">Expected Life Span:</label>
                <select class="form-control" name="expected_life_span" required>
                    <option value="">Select Expected Life Span</option>
                    <option value="Annual (1 year)" <?= $plant['EXPECTED_LIFE_SPAN'] === 'Annual (1 year)' ? 'selected' : ''; ?>>Annual (1 year)</option>
                    <option value="Biennial (2 years)" <?= $plant['EXPECTED_LIFE_SPAN'] === 'Biennial (2 years)' ? 'selected' : ''; ?>>Biennial (2 years)</option>
                    <option value="Perennial (3+ years)" <?= $plant['EXPECTED_LIFE_SPAN'] === 'Perennial (3+ years)' ? 'selected' : ''; ?>>Perennial (3+ years)</option>
                </select>
            </div>

            <div class="form-group my-4">
                <label for="age">Age of Plant:</label>
                <input type="text" class="form-control" name="age" value="<?= htmlspecialchars($plant['AGE']); ?>" required>
            </div>

            <div class="form-group my-4">
                <label for="location">Location:</label>
                <select class="form-control" name="location" required>
                    <option value="">Select Location</option>
                    <option value="Indoor - Living Room" <?= $plant['LOCATION'] === 'Indoor - Living Room' ? 'selected' : ''; ?>>Indoor - Living Room</option>
                    <option value="Indoor - Bedroom" <?= $plant['LOCATION'] === 'Indoor - Bedroom' ? 'selected' : ''; ?>>Indoor - Bedroom</option>
                    <option value="Indoor - Kitchen" <?= $plant['LOCATION'] === 'Indoor - Kitchen' ? 'selected' : ''; ?>>Indoor - Kitchen</option>
                    <option value="Indoor - Office" <?= $plant['LOCATION'] === 'Indoor - Office' ? 'selected' : ''; ?>>Indoor - Office</option>
                    <option value="Indoor - Bathroom" <?= $plant['LOCATION'] === 'Indoor - Bathroom' ? 'selected' : ''; ?>>Indoor - Bathroom</option>
                    <option value="Indoor - Hallway" <?= $plant['LOCATION'] === 'Indoor - Hallway' ? 'selected' : ''; ?>>Indoor - Hallway</option>
                    <option value="Outdoor - Frontyard" <?= $plant['LOCATION'] === 'Outdoor - Frontyard' ? 'selected' : ''; ?>>Outdoor - Frontyard</option>
                    <option value="Outdoor - Vegetable Garden Bed" <?= $plant['LOCATION'] === 'Outdoor - Vegetable Garden Bed' ? 'selected' : ''; ?>>Outdoor - Vegetable Garden Bed</option>
                    <option value="Outdoor - Flower Bed" <?= $plant['LOCATION'] === 'Outdoor - Flower Bed' ? 'selected' : ''; ?>>Outdoor - Flower Bed</option>
                    <option value="Outdoor - Garden" <?= $plant['LOCATION'] === 'Outdoor - Garden' ? 'selected' : ''; ?>>Outdoor - Garden</option>
                    <option value="Outdoor - Backyard" <?= $plant['LOCATION'] === 'Outdoor - Backyard' ? 'selected' : ''; ?>>Outdoor - Backyard</option>
                    <option value="Outdoor - Patio" <?= $plant['LOCATION'] === 'Outdoor - Patio' ? 'selected' : ''; ?>>Outdoor - Patio</option>
                    <option value="Outdoor - Porch" <?= $plant['LOCATION'] === 'Outdoor - Porch' ? 'selected' : ''; ?>>Outdoor - Porch</option>
                    <option value="Outdoor - Balcony" <?= $plant['LOCATION'] === 'Outdoor - Balcony' ? 'selected' : ''; ?>>Outdoor - Balcony</option>
                    <option value="Outdoor - Terrace" <?= $plant['LOCATION'] === 'Outdoor - Terrace' ? 'selected' : ''; ?>>Outdoor - Terrace</option>
                </select>
            </div>

            <div class="form-group my-4">
                <label for="species">Species:</label>
                <input type="text" class="form-control" name="species" value="<?= htmlspecialchars($plant['SPECIES']); ?>" required>
            </div>

            <div class="form-group my-4">
                <input type="submit" value="Update Plant" class="btn btn-primary">
            </div>
        </form>
    </div>
</body>
</html>
