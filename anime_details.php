<?php
session_start();
require 'db.php';

$anime_id = $_GET['id'];

// Fetch anime details with average rating
$query = "SELECT a.*, 
                 IFNULL(AVG(r.rating), 0) AS avg_rating, COUNT(r.id) AS total_reviews
          FROM anime a
          LEFT JOIN reviews r ON a.id = r.anime_id
          WHERE a.id = $anime_id
          GROUP BY a.id";
$anime = $conn->query($query)->fetch_assoc();

// Fetch reviews
$reviews_query = "SELECT u.username, r.rating, r.review, r.created_at 
                  FROM reviews r 
                  JOIN users u ON r.user_id = u.id
                  WHERE r.anime_id = $anime_id";
$reviews = $conn->query($reviews_query);

// Handle review submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['user_id'])) {
    $rating = $_POST['rating'];
    $review = $_POST['review'];
    $user_id = $_SESSION['user_id'];

    $insert_query = "INSERT INTO reviews (anime_id, user_id, rating, review) 
                     VALUES ('$anime_id', '$user_id', '$rating', '$review')";
    $conn->query($insert_query);
    header("Refresh:0"); // Reload page to show new review
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $anime['title']; ?></title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1><?php echo $anime['title']; ?></h1>
    <img src="images/<?php echo $anime['image']; ?>" alt="<?php echo $anime['title']; ?>">
    <p><?php echo $anime['description']; ?></p>
    
    <h2>⭐ <?php echo number_format($anime['avg_rating'], 1); ?> / 5 (<?php echo $anime['total_reviews']; ?> reviews)</h2>

    <h2>Ratings & Reviews</h2>
    <?php while ($review = $reviews->fetch_assoc()) { ?>
        <div class="review">
            <strong><?php echo $review['username']; ?>:</strong>
            <span>⭐ <?php echo $review['rating']; ?>/5</span>
            <p><?php echo $review['review']; ?></p>
            <small><?php echo $review['created_at']; ?></small>
        </div>
    <?php } ?>

    <?php if (isset($_SESSION['user_id'])) { ?>
        <h3>Leave a Review</h3>
        <form method="POST">
            <label for="rating">Rating:</label>
            <select name="rating" required>
                <option value="5">5 - Excellent</option>
                <option value="4">4 - Good</option>
                <option value="3">3 - Average</option>
                <option value="2">2 - Poor</option>
                <option value="1">1 - Terrible</option>
            </select>
            <label for="review">Review:</label>
            <textarea name="review" required></textarea>
            <button type="submit">Submit</button>
        </form>
    <?php } else { ?>
        <p><a href="login.php">Login</a> to leave a review.</p>
    <?php } ?>
</body>
</html>
