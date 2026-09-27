<?php
session_start();
include "config/database.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    if ($email == "" || $password == "") {
        $message = "Please enter email and password.";
    } else {

        $stmt = $conn->prepare(
            "SELECT id, name, password, role FROM users WHERE email = ?"
        );

        $stmt->bind_param("s", $email);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows == 1) {

            $user = $result->fetch_assoc();

            if (password_verify($password, $user["password"])) {

                $_SESSION["user_id"] = $user["id"];
                $_SESSION["user_name"] = $user["name"];
                $_SESSION["role"] = $user["role"];

                if ($user["role"] == "admin") {
                    header("Location: admin/dashboard.php");
                } elseif ($user["role"] == "doctor") {
                    header("Location: doctor/dashboard.php");
                } else {
                    header("Location: patient/dashboard.php");
                }

                exit();

            } else {
                $message = "Invalid email or password.";
            }

        } else {
            $message = "Invalid email or password.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - MediCare Clinic</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

<header>
    <div class="logo">MediCare Clinic</div>

    <nav>
        <a href="index.php">Home</a>
        <a href="register.php">Register</a>
    </nav>
</header>

<div class="form-container">

    <h2>Login</h2>

    <?php if ($message != ""): ?>
        <p class="message"><?php echo $message; ?></p>
    <?php endif; ?>

    <form method="POST">

        <label>Email Address</label>
        <input type="email" name="email" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <button type="submit" class="btn">Login</button>

    </form>

    <p>
        Don't have an account?
        <a href="register.php">Register here</a>
    </p>

</div>

<footer>
    <p>&copy; 2026 MediCare Clinic. All Rights Reserved.</p>
</footer>
<script src="js/script.js"></script>
</body>
</html>