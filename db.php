<?php
// Database credentials
$servername = "localhost"; // Typically 'localhost' for local servers
$username = "root"; // Your MySQL username
$password = "sPreeha1305"; // Your MySQL password
$dbname = "CSE370_PROJECT"; // Your database name
$conn = "";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if ($conn) {
    echo "Connected successfully!!";
}
else {
    echo "Could not connect!";

}

?>

