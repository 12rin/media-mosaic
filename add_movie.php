<?php
session_start();
include 'db.php';

// Only allow admins to add movies
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $genre = $_POST['genre'];
    $actors = $_POST['actors'];
    $rating = $_POST['rating'];
    $year = $_POST['year'];

    // Insert movie (No Image)
    $query = "INSERT INTO movies (title, genre, actors, rating, year) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssdi", $title, $genre, $actors, $rating, $year);

    if ($stmt->execute()) {
        echo "<script>alert('✅ Movie added successfully!'); window.location.href='genre.php?genre=$genre';</script>";
    } else {
        echo "❌ Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Movie</title>
</head>
<body>
    <h2>Add a Movie</h2>
    <form action="" method="post">
        <label>Title:</label>
        <input type="text" name="title" required><br>

        <label>Genre:</label>
        <input type="text" name="genre" required><br>

        <label>Actors:</label>
        <input type="text" name="actors" required><br>

        <label>Rating (1-10):</label>
        <input type="number" step="0.1" min="1" max="10" name="rating" required><br>

        <label>Year:</label>
        <input type="number" name="year" min="1900" max="2025" required><br>

        <button type="submit">Add Movie</button>
    </form>
</body>
</html>


