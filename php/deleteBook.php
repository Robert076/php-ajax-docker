<?php
// deleteBook.php

// Database credentials
$host = 'db';
$username = getenv('MYSQL_USER');
$password = getenv('MYSQL_PASSWORD');
$dbname = getenv('MYSQL_DATABASE');

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

$id = $_POST['id'];

// Delete query
$sql = "DELETE FROM books WHERE id=$id";

if ($conn->query($sql) === TRUE) {
    echo json_encode(["success" => "Book deleted successfully"]);
} else {
    echo json_encode(["error" => "Error: " . $conn->error]);
}

$conn->close();
?>
