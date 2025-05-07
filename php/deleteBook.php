<?php



$host = 'db';
$username = getenv('MYSQL_USER');
$password = getenv('MYSQL_PASSWORD');
$dbname = getenv('MYSQL_DATABASE');


$conn = new mysqli($host, $username, $password, $dbname);


if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

$id = $_POST['id'];


$sql = "DELETE FROM books WHERE id=$id";

if ($conn->query($sql) === TRUE) {
    echo json_encode(["success" => "Book deleted successfully"]);
} else {
    echo json_encode(["error" => "Error: " . $conn->error]);
}

$conn->close();
?>
