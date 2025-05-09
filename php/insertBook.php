<?php
require_once 'headers.php';

$host = 'db';
$username = getenv('MYSQL_USER');
$password = getenv('MYSQL_PASSWORD');
$dbname = getenv('MYSQL_DATABASE');

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

// Get POST data
$author = $_POST['author'] ?? '';
$title = $_POST['title'] ?? '';
$price = $_POST['price'] ?? '';

// Validate input
if (empty($author) || empty($title) || empty($price)) {
    echo json_encode(["error" => "All fields are required"]);
    exit();
}

// Sanitize input
$author = $conn->real_escape_string($author);
$title = $conn->real_escape_string($title);
$price = $conn->real_escape_string($price);

$sql = "INSERT INTO books (author, title, price) VALUES ('$author', '$title', '$price')";

if ($conn->query($sql) === TRUE) {
    echo json_encode(["success" => "New book inserted successfully"]);
} else {
    echo json_encode(["error" => "Error: " . $conn->error]);
}

$conn->close();

?>
