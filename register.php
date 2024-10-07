<?php
// Database connection
$host = 'localhost'; // Replace with your host name
$db   = 'slms'; // Replace with your database name
$user = 'root'; // Replace with your MySQL username
$pass = ''; // Replace with your MySQL password

// Create connection
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Simple validation
    if ($password !== $confirm_password) {
        die("Passwords do not match!");
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Check if the email is already in the database
    $check_email_query = "SELECT * FROM customers WHERE email = ?";
    $stmt = $conn->prepare($check_email_query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        die("Email already exists! Please use a different email.");
    }

    // Insert into database
    $sql = "INSERT INTO customers (name, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $name, $email, $hashed_password);

    if ($stmt->execute()) {
        echo "Registration successful!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <style>
        /* General body styling to match the page */
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #4d5c66;
            /* Same as your dark background color */
            color: #fff;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .registration-form {
            background-color: #333b41;
            /* Slightly lighter background for the form */
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 400px;
        }

        .registration-form h2 {
            margin-bottom: 20px;
            color: #ffcc00;
            /* Use the yellow-ish color for the title to match branding */
            text-align: center;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #fff;
            /* Text color consistent with other elements */
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #666;
            /* Border color to match the theme */
            border-radius: 5px;
            background-color: #4d5c66;
            /* Match background color */
            color: #fff;
        }

        .form-group input:focus {
            border-color: #ffcc00;
            /* Focus border color matches yellow branding */
            outline: none;
        }

        .submit-btn {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 5px;
            background-color: #ffcc00;
            /* Button color matches the yellow in the header */
            color: #333b41;
            font-weight: bold;
            cursor: pointer;
        }

        .submit-btn:hover {
            background-color: #e6b800;
        }

        p {
            text-align: center;
            margin-top: 20px;
            color: #ffcc00;
            /* Text color for links */
        }

        p a {
            color: #ffcc00;
            text-decoration: none;
        }

        p a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="registration-form">
            <h2>MSMS Register</h2>
            <form action="" method="POST">
                <div class="form-group">
                    <label for="name">Full Name:</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirm Password:</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>
                <button type="submit" class="submit-btn">Register</button>
            </form>
            <p>Already have an account? <a href="./c_login.php">Login here</a></p>
            <p>Go to Website <a href="../msms" style="color: white !important;">Click here</a></p>

        </div>
    </div>
</body>

</html>