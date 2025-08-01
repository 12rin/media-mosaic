<?php
session_start();
require 'db.php'; // Database connection

if (isset($_POST['search'])) {
    $query = $_POST['search'];
    $query = mysqli_real_escape_string($conn, $query); // Prevent SQL injection

    // Search movies
    $movies_sql = "SELECT * FROM movies WHERE title LIKE '%$query%'";
    $movies_result = mysqli_query($conn, $movies_sql);
    if (!$movies_result) {
        die('Error in query: ' . mysqli_error($conn)); // Display SQL error if any
    }

    // Search anime
    $anime_sql = "SELECT * FROM anime WHERE title LIKE '%$query%'";
    $anime_result = mysqli_query($conn, $anime_sql);
    if (!$anime_result) {
        die('Error in query: ' . mysqli_error($conn)); // Display SQL error if any
    }

    // Search series
    $series_sql = "SELECT * FROM series WHERE title LIKE '%$query%'";
    $series_result = mysqli_query($conn, $series_sql);
    if (!$series_result) {
        die('Error in query: ' . mysqli_error($conn)); // Display SQL error if any
    }

    // Search bollywood
    $bollywood_sql = "SELECT * FROM bollywood WHERE title LIKE '%$query%'";
    $bollywood_result = mysqli_query($conn, $bollywood_sql);
    if (!$bollywood_result) {
        die('Error in query: ' . mysqli_error($conn)); // Display SQL error if any
    }

    // Display results
    $results = [];
    if (mysqli_num_rows($movies_result) > 0) {
        while ($row = mysqli_fetch_assoc($movies_result)) {
            $results[] = $row; // Store movie results
        }
    }
    if (mysqli_num_rows($anime_result) > 0) {
        while ($row = mysqli_fetch_assoc($anime_result)) {
            $results[] = $row; // Store anime results
        }
    }
    if (mysqli_num_rows($series_result) > 0) {
        while ($row = mysqli_fetch_assoc($series_result)) {
            $results[] = $row; // Store series results
        }
    }
    if (mysqli_num_rows($bollywood_result) > 0) {
        while ($row = mysqli_fetch_assoc($bollywood_result)) {
            $results[] = $row; // Store bollywood results
        }
    }

    // Output the results
    if (count($results) > 0) {
        foreach ($results as $result) {
            // Output the result, adjust as per your requirements
            echo '<div class="result-card">';
            echo '<h3>' . $result['title'] . '</h3>';
            echo '<p>' . $result['description'] . '</p>';

            // For ratings, we can calculate the average rating if necessary.
            if (isset($result['id'])) {
                // Check if the result is from a movie, anime, series, or bollywood
                $type = '';
                $id = $result['id'];
                
                // Get average rating for each type
                if (isset($result['movie_id'])) {
                    $type = 'movie';
                    $rating_sql = "SELECT AVG(rating) AS avg_rating FROM ratings WHERE movie_id = $id";
                } elseif (isset($result['anime_id'])) {
                    $type = 'anime';
                    $rating_sql = "SELECT AVG(rating) AS avg_rating FROM ratings WHERE anime_id = $id";
                } elseif (isset($result['series_id'])) {
                    $type = 'series';
                    $rating_sql = "SELECT AVG(rating) AS avg_rating FROM ratings WHERE series_id = $id";
                } elseif (isset($result['bollywood_id'])) {
                    $type = 'bollywood';
                    $rating_sql = "SELECT AVG(rating) AS avg_rating FROM ratings WHERE bollywood_id = $id";
                }

                // Get average rating
                if ($type) {
                    $rating_result = mysqli_query($conn, $rating_sql);
                    if ($rating_result) {
                        $rating_row = mysqli_fetch_assoc($rating_result);
                        $avg_rating = number_format($rating_row['avg_rating'], 1);
                        echo '<p>Average Rating: ' . $avg_rating . ' ★</p>';
                    }
                }
            }

            echo '</div>';
        }
    } else {
        echo 'No results found.';
    }
}
?>
