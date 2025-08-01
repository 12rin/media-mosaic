<?php
session_start();
require 'db.php'; // Database connection file

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $movie_id = isset($_POST['movie_id']) ? (int)$_POST['movie_id'] : 0;
    $review_text = isset($_POST['review_text']) ? trim($_POST['review_text']) : '';

    // Validate input
    if ($movie_id > 0 && !empty($review_text)) {
        // Insert review into the database
        $stmt = $conn->prepare("INSERT INTO reviews (user_id, movie_id, review_text) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $user_id, $movie_id, $review_text);
        
        if ($stmt->execute()) {
            $_SESSION['message'] = "Review submitted successfully!";
        } else {
            $_SESSION['error'] = "Failed to submit review.";
        }
        $stmt->close();
    } else {
        $_SESSION['error'] = "Invalid review input.";
    }
}

// Redirect back to movies page
header("Location: movies.php");
exit();