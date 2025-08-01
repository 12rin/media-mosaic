<?php
session_start();
include('db.php');

// Check if the user is already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Query the database to check if the email exists
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            // Password is correct, start session
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['email'] = $row['email'];

            // Regenerate session ID to prevent session fixation attacks
            session_regenerate_id(true);

            header("Location: dashboard.php");
            exit();
        } else {
            // Invalid password error
            $error_message = "Invalid password!";
        }
    } else {
        // No user found with that email
        $error_message = "No user found with that email!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        /* Body background */
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;  /* Light gray background */
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
            background-image: url('ne.jpeg');  /* Optional background image */
            background-size: cover;
            background-position: center;
        }

        .form-container {
            background-color: rgba(12, 12, 12, 0.74);  /* White background with slight transparency */
            padding: 30px;
            border-radius: 8px;
            width: 300px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        h2 {
            font-size: 24px;
            margin-bottom: 20px;
            color: white; /* Changed to white */
        }

        .logo {
            width: 80px;
            height: 80px;
            margin-bottom: 20px;
        }

        .input-group {
            margin-bottom: 15px;
            text-align: left;
        }

        .input-group label {
            display: block;
            margin-bottom: 5px;
            color: white; /* Changed to white */
        }

        .input-group input {
            width: 100%;
            padding: 10px;
            border-radius: 4px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            padding: 10px;
            border: none;
            background-color: #28a745;
            color: white;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #218838;
        }

        .error-message {
            color: red;
            font-size: 14px;
            margin-bottom: 10px;
        }

        p {
            font-size: 14px;
            color: white; /* Changed to white */
        }

        a {
            color: white; /* Changed to white */
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="form-container">
        <!-- Logo section, update with your logo image path -->
        <img src="p.jpeg" alt="Logo" class="logo">

        <h2>Login</h2>

        <!-- Error Message if login fails -->
        <?php
        if (isset($error_message)) {
            echo "<p class='error-message'>$error_message</p>";
        }
        ?>

        <form action="index.php" method="POST">
            <div class="input-group">
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" required>
            </div>
            <div class="input-group">
                <label for="password">Password:</label>
                <input type="password" name="password" id="password" required>
            </div>
            <button type="submit">Login</button>
        </form>

        <p>Don't have an account? <a href="signup.php">Sign Up</a></p>
    </div>

</body>
</html>
