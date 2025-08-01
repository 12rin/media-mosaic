

<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if user is logged in
    if (!isset($_SESSION['username'])) {
        echo "You must be logged in to submit a review.";
        exit();
    }

    // Get user role from the database
    $username = $_SESSION['username'];
    $user_check_query = "SELECT role FROM users WHERE username = ?";
    $stmt = $conn->prepare($user_check_query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 0) {
        echo "User not found.";
        exit();
    }

    $row = $result->fetch_assoc();
    $user_role = $row['role'];

    // Ensure only 'user' role can submit reviews
    if ($user_role !== 'user') {
        echo "Only users can submit reviews. Admins are not allowed.";
        exit();
    }

    // Validate inputs
    if (empty($_POST['movie_id']) || empty($_POST['review']) || empty($_POST['rating'])) {
        echo "All fields are required.";
        exit();
    }

    // Sanitize inputs
    $movie_id = intval($_POST['movie_id']);
    $review = htmlspecialchars($_POST['review'], ENT_QUOTES, 'UTF-8');
    $rating = intval($_POST['rating']);

    // Validate rating range (1-10)
    if ($rating < 1 || $rating > 10) {
        echo "Rating must be between 1 and 10.";
        exit();
    }

    // Insert into database (Remove genre)
    $query = "INSERT INTO reviews (movie_id, user_name, review_text, rating) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("isss", $movie_id, $username, $review, $rating);
    
    if ($stmt->execute()) {
        header("Location: review.php?movie_id=" . urlencode($movie_id));
        exit();
    } else {
        echo "Error submitting review: " . $stmt->error;
    }
}
?>
