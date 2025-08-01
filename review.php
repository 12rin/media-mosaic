
<?php
session_start();
include 'db.php'; // Include database connection

if (!isset($_GET['movie_id'])) {
    die('Movie ID not provided.');
}

$movie_id = intval($_GET['movie_id']);

// Fetch movie details
$movieQuery = $conn->prepare("SELECT title, year FROM movies WHERE id = ?");
$movieQuery->bind_param("i", $movie_id);
$movieQuery->execute();
$movieResult = $movieQuery->get_result();
$movie = $movieResult->fetch_assoc();

if (!$movie) {
    die('Movie not found.');
}

// Fetch reviews
$reviewQuery = $conn->prepare("SELECT user_name, review_text, rating, created_at FROM reviews WHERE movie_id = ? ORDER BY created_at DESC");

if (!$stmt) {
    die("SQL Error: " . $conn->error);
}
$reviewQuery->bind_param("i", $movie_id);
$reviewQuery->execute();
$reviews = $reviewQuery->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reviews for <?= htmlspecialchars($movie['title']) ?> (<?= $movie['year'] ?>)</title>
    <link rel="stylesheet" href="styles.css"> <!-- Ensure this file exists -->
</head>
<body>
    <h2>Reviews for <?= htmlspecialchars($movie['title']) ?> (<?= $movie['year'] ?>)</h2>
    
    <a href="index.php">Back to Home</a>

    <h3>User Reviews</h3>
    <?php if ($reviews->num_rows > 0): ?>
        <ul>
            <?php while ($review = $reviews->fetch_assoc()): ?>
                <li>
                    <strong><?= htmlspecialchars($review['user_name']); ?>:</strong>
                    (Rating: <?= $review['rating']; ?>/10)
                    <p><?= nl2br(htmlspecialchars($review['review_text'])); ?></p>
                    <small>Posted on <?= $review['created_at']; ?></small>
                </li>
            <?php endwhile; ?>
        </ul>
    <?php else: ?>
        <p>No reviews yet. Be the first to review!</p>
    <?php endif; ?>

    <?php if (isset($_SESSION['username'])): ?>
        <h3>Submit Your Review</h3>
        <form action="submit_review.php" method="POST">
            <input type="hidden" name="movie_id" value="<?= $movie_id; ?>">
            <input type="hidden" name="user_name" value="<?= $_SESSION['username']; ?>"> <!-- Get username from session -->
            <label>Rating (1-10):</label>
            <input type="number" name="rating" min="1" max="10" required>
            <label>Review:</label>
            <textarea name="review_text" required></textarea>
            <button type="submit">Submit Review</button>
        </form>
    <?php else: ?>
        <p><strong>You must <a href="index.php">log in</a> to submit a review.</strong></p>
    <?php endif; ?>
</body>
</html>
