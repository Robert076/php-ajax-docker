// updateBook.php
<?php
// Database credentials
$host = 'db';  
$username = getenv('MYSQL_USER');
$password = getenv('MYSQL_PASSWORD');
$dbname = getenv('MYSQL_DATABASE');

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

$id = $_POST['id'];
$author = $_POST['author'];
$title = $_POST['title'];
$price = $_POST['price'];

$sql = "UPDATE books SET author = ?, title = ?, price = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ssdi', $author, $title, $price, $id);

if ($stmt->execute()) {
    echo json_encode(["success" => "Book updated successfully"]);
} else {
    echo json_encode(["error" => "Error updating book"]);
}

$stmt->close();
$conn->close();
?>
