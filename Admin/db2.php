<?php

$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "abc2";

$cattitle = filter_input(INPUT_POST,'categorytitle');

// Create connection
$conn = new mysqli($servername, $username, $password,$dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
//echo "Connected successfully";

//$conn->close();

?>

