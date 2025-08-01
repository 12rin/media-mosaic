<?php
session_start();
require 'db.php'; 

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "Not logged in"]);
    exit();
}

$user_id = $_SESSION['user_id'];
$item_id = $_POST['item_id'] ?? null;  // This will handle both movie_id and anime_id
$item_type = $_POST['item_type'] ?? null;  // Type of item: "movie" or "anime"

if (!$item_id || !$item_type) {
    echo json_encode(["status" => "error", "message" => "Invalid request"]);
    exit();
}

// Check if the item is already in the watchlist
$check_query = "SELECT * FROM watchlist WHERE user_id = ? AND item_id = ? AND item_type = ?";
$stmt = $conn->prepare($check_query);
$stmt->bind_param("iis", $user_id, $item_id, $item_type);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Remove item from watchlist
    $delete_query = "DELETE FROM watchlist WHERE user_id = ? AND item_id = ? AND item_type = ?";
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param("iis", $user_id, $item_id, $item_type);
    $stmt->execute();
    echo json_encode(["status" => "success", "action" => "removed"]);
} else {
    // Add item to watchlist
    $insert_query = "INSERT INTO watchlist (user_id, item_id, item_type) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($insert_query);
    $stmt->bind_param("iis", $user_id, $item_id, $item_type);
    $stmt->execute();
    echo json_encode(["status" => "success", "action" => "added"]);
}
?>
