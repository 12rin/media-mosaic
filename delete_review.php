<?php
session_start();
require 'db.php'; // Database connection file

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if (isset($_GET['review_id']) && isset($_GET['type'])) {
    $review_id = (int)$_GET['review_id'];
    $user_id = $_SESSION['user_id'];
    $type = $_GET['type']; // Detect which page user was on

    // Validate type (anime, movie, series, top30movies)
    $valid_types = ['anime', 'movie', 'series', 'top30movies'];
    if (!in_array($type, $valid_types)) {
        exit("Invalid type.");
    }

    // Set the correct column based on type
    $column = $type . "_id";

    // Check if the review belongs to the logged-in user
    $check_query = $conn->prepare("SELECT * FROM reviews WHERE id = ? AND user_id = ?");
    $check_query->bind_param("ii", $review_id, $user_id);
    $check_query->execute();
    $result = $check_query->get_result();

    if ($result->num_rows > 0) {
        // Delete the review
        $delete_query = $conn->prepare("DELETE FROM reviews WHERE id = ?");
        $delete_query->bind_param("i", $review_id);
        if ($delete_query->execute()) {
            $_SESSION['message'] = "Review deleted successfully.";
        } else {
            $_SESSION['error'] = "Error deleting review.";
        }
    } else {
        $_SESSION['error'] = "You are not authorized to delete this review.";
    }

    $check_query->close();
    $delete_query->close();
}

// Redirect back to the correct page
header("Location: " . htmlspecialchars($type) . ".php");
exit();


?>
