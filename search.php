 <?php
session_start();
require 'db.php'; // Database connection

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search - Media Mosaic</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
   
    <style>
    body {
        background-color: #0e0e0e;
        color: #f1f1f1;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        margin: 0;
        padding: 0;
    }

    .search-container {
        text-align: center;
        padding: 40px 20px 20px;
    }

    .search-container h1 {
        margin-bottom: 20px;
        font-size: 2rem;
        color:rgb(255, 217, 0);
    }

    input[type="text"] {
        width: 60%;
        max-width: 600px;
        padding: 12px 20px;
        font-size: 1.1rem;
        border-radius: 30px;
        border: 2px solidrgb(255, 251, 0);
        background-color: #1a1a1a;
        color: white;
        transition: 0.3s;
    }

    input[type="text"]:focus {
        border-color:rgb(191, 146, 0);
        background-color: #222;
    }

    .results {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        margin-top: 30px;
        gap: 20px;
    }

    .result-card {
        background: #1c1c1c;
        border: 1px solid #2c2c2c;
        border-radius: 12px;
        padding: 15px;
        width: 220px;
        text-align: center;
        box-shadow: 0 4px 12px rgba(0, 255, 195, 0.05);
        transition: transform 0.3s, background 0.3s;
        cursor: pointer;
    }

    .result-card:hover {
        background:rgb(255, 174, 0);
        color: #000;
        transform: translateY(-5px);
    }

    .result-card img {
        width: 100%;
        border-radius: 10px;
        margin-bottom: 10px;
    }

    .navbar {
        background: #121212;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px 30px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.4);
    }

    .logo {
        font-size: 1.6rem;
        font-weight: bold;
        color:rgb(255, 187, 0);
    }

    .nav a {
        color: #f1f1f1;
        margin: 0 15px;
        text-decoration: none;
        font-weight: 500;
        transition: color 0.3s;
    }

    .nav a:hover {
        color:rgb(255, 174, 0);
    }

    .logout-btn {
        color: #ff4d4d;
        font-weight: bold;
    }
</style>


    <script>
        $(document).ready(function() {
            $("#search-input").on("input", function() {
                var query = $(this).val();
                if (query.length > 1) {
                    $.ajax({
                        type: "POST",
                        url: "search_results.php",
                        data: { search: query },
                        success: function(response) {
                            $(".results").html(response);
                        }
                    });
                } else {
                    $(".results").html("");
                }
            });
        });
    </script>
</head>
<body>

    <header class="navbar">
        <div class="nav-left">
            <i class="fas fa-bars menu-icon"></i>
            <div class="logo">Media Mosaic</div>
        </div>
        <nav class="nav">
            <a href="dashboard.php">Home</a>
            <a href="search.php">Search</a>
            <a href="watchlist.php">Watchlist</a>
            <a href="logout.php" class="logout-btn">Logout</a>
        </nav>
    </header>

    <div class="search-container">
        <h1>Search Movies, Anime, or Series</h1>
        <input type="text" id="search-input" placeholder="Type to search...">
    </div>

    <div class="results"></div>

</body>
</html>

