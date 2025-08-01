<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["message" => "Please log in first."]);
    exit;
}

$user_id = $_SESSION['user_id'];
$media_id = $_POST['item_id'];
$media_type = $_POST['item_type']; // movie, anime, series, bollywood

// Validate the input
if (empty($media_id) || empty($media_type)) {
    echo json_encode(["message" => "Invalid input."]);
    exit;
}

// Delete from watchlist
$sql = "DELETE FROM watchlist WHERE user_id = ? AND media_id = ? AND item_type = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iis", $user_id, $media_id, $media_type);

if ($stmt->execute()) {
    echo json_encode(["message" => "Removed from watchlist!"]);
} else {
    echo json_encode(["message" => "Failed to remove from watchlist: " . $conn->error]);
}
?>
