<?php
$db_host = "localhost"; // Your database host (usually 'localhost' for local development)
$db_user = "root"; // Your database username
$db_password = ""; // Your database password (leave blank if there's no password)
$db_name = "timeline_data"; // Your database name

// Create a connection to the database
$con = new mysqli($db_host, $db_user, $db_password, $db_name);

// Check connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}
?>
