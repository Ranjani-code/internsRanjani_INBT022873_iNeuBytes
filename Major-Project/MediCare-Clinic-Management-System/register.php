<?php
session_start();
include "config/database.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    if ($name == "" || $email == "" || $password == "") {
        $message = "Please fill in all fields.";
    } else {

        $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $result = $check->get_result();

        if ($result->num_rows > 0) {
            $message = "Email already registered.";
        } else {

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare(
                "INSERT INTO users (name, email, password, role)
                 VALUES (?, ?, ?, 'patient')"
            );

            $stmt->bind_param("sss", $name, $email, $hashedPassword);

            if ($stmt->execute()) {

                $userId = $stmt->insert_id;

                $patient = $conn->prepare(
                    "INSERT INTO patients (user_id) VALUES (?)"
                );

                $patient->bind_param("i", $userId);
                $patient->execute();

                $message = "Registration successful. You can now login.";
            } else {
                $message = "Registration failed. Please try again.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Registration - MediCare Clinic</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

<header>
    <div class="logo">MediCare Clinic</div>

    <nav>
        <a href="index.php">Home</a>
        <a href="login.php">Login</a>
    </nav>
</header>

<div class="form-container">

    <h2>Create Patient Account</h2>

    <?php if ($message != ""): ?>
        <p class="message"><?php echo $message; ?></p>
    <?php endif; ?>

    <form method="POST">

        <label>Full Name</label>
        <input type="text" name="name" required>

        <label>Email Address</label>
        <input type="email" name="email" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <button type="submit" class="btn">Register</button>

    </form>

    <p>
        Already have an account?
        <a href="login.php">Login here</a>
    </p>

</div>

<footer>
    <p>&copy; 2026 MediCare Clinic. All Rights Reserved.</p>
</footer>
<script src="js/script.js"></script>
</body>
</html>