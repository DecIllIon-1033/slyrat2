<?php
$conn = new mysqli('localhost', 'root', 'root', 'bukva');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>