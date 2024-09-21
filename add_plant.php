<?php
echo "PHP is working";
?>

<?php
include 'db.php';
session_start();
//plants infoo
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
    $owner_id = $_SESSION['user_id']; // Get the currently logged-in user's ID

    // Prepare and execute the SQL statement
    $stmt = $conn->prepare("INSERT INTO plants 
        (OWNER_ID, NAME, CATEGORY, SUNLIGHT_INFO, POT_SIZE, FERTILIZER, LEAF_CONDITION, WATERING_STATUS, EXPECTED_LIFE_SPAN, AGE, LOCATION, SPECIES) 
        VALUES 
        (:owner_id, :name, :category, :sunlight, :pot_size, :fertilizer, :leaf_condition, :watering_status, :expected_life_span, :age, :location, :species)");

    $stmt->bindParam(':owner_id', $owner_id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':category', $category);
    $stmt->bindParam(':sunlight', $sunlight);
    $stmt->bindParam(':pot_size', $pot_size);
    $stmt->bindParam(':fertilizer', $fertilizer);
    $stmt->bindParam(':leaf_condition', $leaf_condition);
    $stmt->bindParam(':watering_status', $watering_status);
    $stmt->bindParam(':expected_life_span', $expected_life_span);
    $stmt->bindParam(':age', $age);
    $stmt->bindParam(':location', $location);
    $stmt->bindParam(':species', $species);
    $stmt->execute();

    echo "Plant added successfully!";
}
?>

<form action="add_plant.php" method="POST">
    <label for="name">Plant Name:</label>
    <input type="text" name="name" required><br>

    <label for="category">Category:</label>
    <input type="text" name="category" required><br>

    <label for="sunlight">Sunlight Info:</label>
    <input type="text" name="sunlight" required><br>

    <label for="pot_size">Pot Size:</label>
    <input type="text" name="pot_size" required><br>

    <label for="fertilizer">Fertilizer:</label>
    <input type="text" name="fertilizer" required><br>

    <label for="leaf_condition">Leaf Condition:</label>
    <input type="text" name="leaf_condition" required><br>

    <label for="watering_status">Watering Status:</label>
    <input type="text" name="watering_status" required><br>

    <label for="expected_life_span">Expected Life Span:</label>
    <input type="text" name="expected_life_span" required><br>

    <label for="age">Age of Plant:</label>
    <input type="text" name="age" required><br>

    <label for="location">Location:</label>
    <input type="text" name="location" required><br>

    <label for="species">Species:</label>
    <input type="text" name="species" required><br>

    <input type="submit" value="Add Plant">
</form>
