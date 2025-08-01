<?php
session_start();
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
    <title>Media Mosaic - Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $(".menu-icon").click(function() {
                $("#menu-dropdown").toggleClass("show");
            });
        });
    </script>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #111;
        }
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #222;
            padding: 15px;
            color: white;
        }
        .nav-left {
            display: flex;
            align-items: center;
        }
        .menu-icon {
            cursor: pointer;
            font-size: 1.5em;
            margin-right: 10px;
            padding: 10px;
            background: #444;
            border-radius: 5px;
            transition: 0.3s;
        }
        .menu-icon:hover {
            background:rgb(255, 119, 0);
            color: black;
        }
        .logo {
            font-size: 1.5em;
            font-weight: bold;
            color:rgb(255, 166, 0);
        }
        .menu-container {
            position: absolute;
            top: 50px;
            left: 10px;
            background: #333;
            display: none;
            flex-direction: column;
            padding: 10px;
            border-radius: 5px;
        }
        .menu-container.show {
            display: flex;
        }
        .menu-container a {
            color: white;
            text-decoration: none;
            padding: 10px;
            background: #444;
            border-radius: 5px;
            margin-bottom: 5px;
            transition: 0.3s;
        }
        .menu-container a:hover {
            background:rgb(255, 119, 0);
            color: black;
        }
        .nav ul {
            list-style: none;
            display: flex;
            gap: 10px;
            margin: 0;
            padding: 0;
        }
        .nav {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .nav a {
            color: white;
            text-decoration: none;
            padding: 10px 15px;
            background: #444;
            border-radius: 5px;
            transition: 0.3s;
        }
        .nav a:hover {
            background:rgb(255, 225, 0);
            color: black;
        }
        .logout-btn {
            background-color: red !important;
        }

        .hero {
    display: flex;
    justify-content: center; /* Horizontally center the content */
    align-items: flex-start; /* Align content to the top */
    text-align: center;
    padding-top: 180px; /* Adjust this value slightly to move it down a bit */
    /*background: url('images/vi.jpeg') no-repeat center center;*/
    background: url('a.jpeg.jpg') no-repeat center center;
    background-size: cover;
    background-attachment: fixed;
    height: 100vh;  /* Full screen height */
    width: 100%;
    color: cyan;
    background-position: center center;
    background-repeat: no-repeat;
}

.hero-content {
    display: flex;
    flex-direction: column;  /* Align text vertically */
    justify-content: center; /* Keep the content vertically centered inside */
    align-items: center;     /* Align content horizontally */
}

.hero-content h1 {
    font-size: 4rem;
    margin: 0;
    text-shadow: 2px 2px 10px rgba(0, 0, 0, 0.6); /* Added shadow for better contrast */
}

.hero-content p {
    font-size: 1.5rem;
    margin-top: 20px; /* Space between the heading and paragraph */
    text-shadow: 1px 1px 5px rgba(0, 0, 0, 0.6); /* Added shadow for readability */
}
.hero-content h1 {
    font-size: 4rem;
    margin: 0;
    text-shadow: 2px 2px 10px rgba(0, 0, 0, 0.6); /* Added shadow for better contrast */
    color:rgb(127, 41, 26);  /* Change this color to your preferred color */
}

.hero-content p {
    font-size: 1.5rem;
    margin-top: 20px; /* Space between the heading and paragraph */
    text-shadow: 1px 1px 5px rgba(0, 0, 0, 0.6); /* Added shadow for readability */
    color:rgb(164, 130, 130);  /* Color for the paragraph (optional) */
}
    </style>
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
    <div id="menu-dropdown" class="menu-container">
        <a href="bollywood.php">Indian Films</a>
        <a href="anime.php">Anime</a>
        <a href="movies.php">Movies</a>
        <a href="series.php">Series</a>
    </div>
    <section class="hero">
        <div class="hero-content">
            <h1>Welcome to Media Mosaic!</h1>
            <p>Your personalized movie and TV experience.</p>
        </div>
    </section>
    <footer>
        <p>&copy; 2024 Media Mosaic | All rights reserved.</p>
    </footer>
</body>
</html>
