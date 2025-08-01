<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    echo "<p class='error'>Please log in to view your watchlist.</p>";
    exit;
}

$user_id = $_SESSION['user_id'];

// Modified SQL query to fetch movies, series, bollywood, and anime watchlist items
$sql = "SELECT w.*, 
        m.title AS movie_title, 
        a.title AS anime_title, 
        s.title AS series_title, 
        b.title AS bollywood_title
    FROM watchlist w
    LEFT JOIN movies m ON w.media_id = m.id AND w.item_type = 'movie'
    LEFT JOIN anime a ON w.media_id = a.id AND w.item_type = 'anime'
    LEFT JOIN series s ON w.media_id = s.id AND w.item_type = 'series'
    LEFT JOIN bollywood b ON w.media_id = b.id AND w.item_type = 'bollywood'
    WHERE w.user_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if (!$result) {
    die("<p class='error'>Error fetching watchlist: " . mysqli_error($conn) . "</p>");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Watchlist</title>
    <style>
        body {
            background-color: #121212;
            color: white;
            font-family: Arial, sans-serif;
            text-align: center;
            margin: 20px;
        }
        h2 {
            color: #f8b400;
        }
        .watchlist-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 15px;
        }
        .watchlist-item {
            background-color: #1e1e1e;
            border-radius: 10px;
            padding: 15px;
            width: 200px;
            text-align: center;
            box-shadow: 0px 4px 6px rgba(255, 255, 255, 0.1);
        }
        .watchlist-item:hover {
            box-shadow: 0px 6px 10px rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
        }
        .remove-btn {
            background-color: red;
            color: white;
            border: none;
            padding: 8px;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s;
        }
        .remove-btn:hover {
            background-color: darkred;
        }
    </style>
</head>
<body>

<h2>Your Watchlist</h2>
<div class="watchlist-container">

<?php
while ($row = $result->fetch_assoc()) {
    echo "<div class='watchlist-item'>";
    if (!empty($row['movie_title'])) {
        echo "<p>🎬 Movie: " . $row['movie_title'] . "</p>";
        echo "<button class='remove-btn' onclick='removeFromWatchlist(" . $row['media_id'] . ", \"movie\")'>Remove</button>";
    } elseif (!empty($row['anime_title'])) {
        echo "<p>📺 Anime: " . $row['anime_title'] . "</p>";
        echo "<button class='remove-btn' onclick='removeFromWatchlist(" . $row['media_id'] . ", \"anime\")'>Remove</button>";
    } elseif (!empty($row['series_title'])) {
        echo "<p>📺 Series: " . $row['series_title'] . "</p>";
        echo "<button class='remove-btn' onclick='removeFromWatchlist(" . $row['media_id'] . ", \"series\")'>Remove</button>";
    } elseif (!empty($row['bollywood_title'])) {
        echo "<p>🎥 Bollywood: " . $row['bollywood_title'] . "</p>";
        echo "<button class='remove-btn' onclick='removeFromWatchlist(" . $row['media_id'] . ", \"bollywood\")'>Remove</button>";
    }
    echo "</div>";
}
?>

</div>

<script>
function removeFromWatchlist(item_id, item_type) {
    fetch('remove_watchlist.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: new URLSearchParams({
            item_id: item_id,
            item_type: item_type
        })
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
        location.reload(); // Reload to reflect changes
    })
    .catch(error => console.error('Error:', error));
}
</script>
</body>
</html>
