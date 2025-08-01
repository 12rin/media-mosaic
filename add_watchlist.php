<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["message" => "Please log in to add to your watchlist."]);
    exit;
}

$user_id = $_SESSION['user_id'];
$media_id = $_POST['media_id'];
$media_type = $_POST['media_type']; // movie, anime, series, bollywood

// Validate the input
if (empty($media_id) || empty($media_type)) {
    echo json_encode(["message" => "Invalid input."]);
    exit;
}

// Prevent adding the same item again
$sql_check = "SELECT * FROM watchlist WHERE user_id = ? AND media_id = ? AND item_type = ?";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param("iis", $user_id, $media_id, $media_type);
$stmt_check->execute();
$result_check = $stmt_check->get_result();

if ($result_check->num_rows > 0) {
    echo json_encode(["message" => "This item is already in your watchlist."]);
    exit;
}

// Insert into watchlist
$sql = "INSERT INTO watchlist (user_id, media_id, item_type) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iis", $user_id, $media_id, $media_type);

if ($stmt->execute()) {
    echo json_encode(["message" => "Added to watchlist!"]);
} else {
    echo json_encode(["message" => "Failed to add to watchlist: " . $conn->error]);
}
?>
