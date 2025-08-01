<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id']) || !isset($_POST['media_id']) || !isset($_POST['rating']) || !isset($_POST['media_type'])) {
    http_response_code(400);
    echo "Invalid request.";
    exit();
}

$user_id = $_SESSION['user_id'];
$media_id = $_POST['media_id'];
$rating = $_POST['rating'];
$media_type = $_POST['media_type']; // movie / anime / series / bollywood

$table = "ratings";

$stmt = $conn->prepare("SELECT id FROM $table WHERE user_id = ? AND {$media_type}_id = ?");
$stmt->bind_param("ii", $user_id, $media_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $stmt = $conn->prepare("UPDATE $table SET rating = ? WHERE user_id = ? AND {$media_type}_id = ?");
    $stmt->bind_param("iii", $rating, $user_id, $media_id);
} else {
    $stmt = $conn->prepare("INSERT INTO $table (user_id, {$media_type}_id, rating) VALUES (?, ?, ?)");
    $stmt->bind_param("iii", $user_id, $media_id, $rating);
}

$stmt->execute();
echo "Rating submitted!";
?>
