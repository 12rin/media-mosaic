<?php
session_start();
require 'db.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Handle Review Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['media_id'], $_POST['review_text'], $_POST['media_type']) && $_POST['media_type'] === 'anime') {
    $media_id = $_POST['media_id'];
    $review_text = $_POST['review_text'];

    $stmt = $conn->prepare("INSERT INTO reviews (user_id, anime_id, review_text) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $user_id, $media_id, $review_text);
    $stmt->execute();

    header("Location: anime.php");
    exit();
}

// Pagination setup
$anime_per_page = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $anime_per_page;

// Fetch anime
$sql = "SELECT * FROM anime LIMIT $anime_per_page OFFSET $offset";
$result = $conn->query($sql);

// Total pages
$total_anime_query = $conn->query("SELECT COUNT(*) as count FROM anime");
$total_anime = $total_anime_query->fetch_assoc()['count'];
$total_pages = ceil($total_anime / $anime_per_page);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Anime</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            background: #121212;
            color: #fff;
            font-family: Arial;
            padding: 20px;
        }
        .anime-container {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
            justify-items: center;
        }
        .anime-card {
            background: #1e1e1e;
            padding: 20px;
            border-radius: 10px;
            width: 100%;
            max-width: 300px;
            box-shadow: 0 4px 20px rgba(255,255,255,0.2);
        }
        .anime-card img {
            width: 100%;
            height: 300px;
            object-fit: cover;
            border-radius: 8px;
        }
        .anime-card h2 {
            font-size: 1.5rem;
        }
        .rate input {
            display: none;
        }
        .rate label {
            font-size: 24px;
            color: gray;
            cursor: pointer;
        }
        .rate input:checked ~ label,
        .rate label:hover,
        .rate label:hover ~ label {
            color: gold;
        }
        textarea {
            width: 100%;
            padding: 8px;
            border-radius: 5px;
            margin-top: 10px;
        }
        /*.pagination a {
            background: #333;
            color: white;
            padding: 10px 15px;
            margin: 5px;
            border-radius: 5px;
            text-decoration: none;
        }
        .pagination .active {
            background: #ff4500;
        }*/
        .pagination {
    text-align: center;
    margin-top: 20px;
}
.pagination a {
            background: #00bcd4;
            color: #fff;
            padding: 10px 15px;
            margin: 5px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            transition: background 0.3s, transform 0.2s;
        }

        .pagination a:hover {
            background: #0097a7;
            transform: scale(1.1);
        }

        .home-button {
        position: absolute;
        top: 10px;
        right: 10px;
        background: #00bcd4;
        color: white;
        padding: 10px 15px;
        border-radius: 5px;
        text-decoration: none;
        font-weight: bold;
        transition: background 0.3s, transform 0.2s;
    }

    .home-button:hover {
        background: #0097a7;
        transform: scale(1.1);
    }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
   $(document).on("click", ".watchlist-button", function() {
    var mediaId = $(this).data("id");
    var mediaType = $(this).data("type");

    $.post("add_watchlist.php", {
        media_id: mediaId,
        media_type: mediaType
    }, function(response) {
        alert(response); // JSON-safe
    }).fail(function(xhr, status, error) {
        alert("Failed to add to watchlist: " + xhr.responseText);
    });
});

    $(document).on("click", ".rate input", function() {
        var animeId = $(this).data("anime");
        var rating = $(this).val();
        $.post("rate.php", {
            media_id: animeId,
            rating: rating,
            media_type: "anime"
        }, function(response) {
            alert(response);
            location.reload();
        });
    });
</script>

</head>
<body>
    <a href="dashboard.php" class="home-button">Home</a>
    <h1>Anime</h1>
    <div class="anime-container">
        <?php while ($anime = $result->fetch_assoc()) {
            $anime_id = $anime['id'];
            $avg_result = $conn->query("SELECT AVG(rating) as avg_rating FROM ratings WHERE anime_id = $anime_id");
            $avg_rating = number_format($avg_result->fetch_assoc()['avg_rating'] ?? 0, 1);
        ?>
        <div class="anime-card">
        <img src="<?= $anime['image_url'] ?>" alt="Poster">


            <h2><?= $anime['title'] ?></h2>
            <p><?= $anime['description'] ?></p>
            <p>Average Rating: <?= $avg_rating ?> ★</p>

            <div class="rate">
                <?php for ($i = 5; $i >= 1; $i--) { ?>
                    <input type="radio" id="star<?= $i . '_' . $anime_id ?>" name="rating<?= $anime_id ?>" value="<?= $i ?>" data-anime="<?= $anime_id ?>">
                    <label for="star<?= $i . '_' . $anime_id ?>">★</label>
                <?php } ?>
            </div>

            <button class="watchlist-button" data-id="<?= $anime_id ?>" data-type="anime">Add to Watchlist</button>


            <h3>Reviews</h3>
            <div class="reviews">
                <?php
                $reviews_query = "SELECT reviews.review_text, users.email FROM reviews JOIN users ON reviews.user_id = users.id WHERE anime_id = $anime_id";
                $reviews_result = $conn->query($reviews_query);
                while ($review = $reviews_result->fetch_assoc()) {
                    echo "<p><strong>{$review['email']}:</strong> {$review['review_text']}</p>";
                }
                ?>
            </div>

            <form method="POST" action="anime.php">
                <input type="hidden" name="media_id" value="<?= $anime_id ?>">
                <input type="hidden" name="media_type" value="anime">
                <textarea name="review_text" placeholder="Write your review..." required></textarea>
                <button type="submit">Submit Review</button>
            </form>
        </div>
        <?php } ?>
    </div>

    <div class="pagination">
        <?php for ($i = 1; $i <= $total_pages; $i++) { ?>
            <a href="anime.php?page=<?= $i ?>" class="<?= ($i == $page) ? 'active' : '' ?>"><?= $i ?></a>
        <?php } ?>
    </div>
</body>
</html>
