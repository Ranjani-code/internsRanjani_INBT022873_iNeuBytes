<?php
session_start();
include "../config/database.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "patient") {
    header("Location: ../login.php");
    exit();
}

$userId = $_SESSION["user_id"];

$stmt = $conn->prepare("
    SELECT u.name, u.email, p.phone, p.address, p.date_of_birth
    FROM users u
    JOIN patients p ON u.id = p.user_id
    WHERE u.id = ?
");

$stmt->bind_param("i", $userId);
$stmt->execute();

$result = $stmt->get_result();
$patient = $result->fetch_assoc();

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $phone = trim($_POST["phone"]);
    $address = trim($_POST["address"]);
    $dob = $_POST["date_of_birth"];

    $update = $conn->prepare("
        UPDATE patients
        SET phone = ?, address = ?, date_of_birth = ?
        WHERE user_id = ?
    ");

    $update->bind_param("sssi", $phone, $address, $dob, $userId);

    if ($update->execute()) {
        $message = "Profile updated successfully.";

        $stmt->execute();
        $result = $stmt->get_result();
        $patient = $result->fetch_assoc();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - MediCare Clinic</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

<header>
    <div class="logo">MediCare Clinic</div>

    <nav>
        <a href="dashboard.php">Dashboard</a>
        <a href="appointments.php">Appointments</a>
        <a href="book.php">Book Appointment</a>
        <a href="../logout.php">Logout</a>
    </nav>
</header>

<div class="form-container">

    <h2>My Profile</h2>

    <?php if ($message != ""): ?>
        <p class="message"><?php echo $message; ?></p>
    <?php endif; ?>

    <form method="POST">

        <label>Full Name</label>
        <input type="text"
               value="<?php echo htmlspecialchars($patient["name"]); ?>"
               readonly>

        <label>Email Address</label>
        <input type="email"
               value="<?php echo htmlspecialchars($patient["email"]); ?>"
               readonly>

        <label>Phone Number</label>
        <input type="text"
               name="phone"
               value="<?php echo htmlspecialchars($patient["phone"] ?? ""); ?>">

        <label>Address</label>
        <textarea name="address"><?php echo htmlspecialchars($patient["address"] ?? ""); ?></textarea>

        <label>Date of Birth</label>
        <input type="date"
               name="date_of_birth"
               value="<?php echo htmlspecialchars($patient["date_of_birth"] ?? ""); ?>">

        <button type="submit" class="btn">Update Profile</button>

    </form>

</div>

<footer>
    <p>&copy; 2026 MediCare Clinic. All Rights Reserved.</p>
</footer>
<script src="../js/script.js"></script>
</body>
</html>