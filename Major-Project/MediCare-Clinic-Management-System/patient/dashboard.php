<?php
session_start();
include "../config/database.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "patient") {
    header("Location: ../login.php");
    exit();
}

$userId = $_SESSION["user_id"];
$name = $_SESSION["user_name"];

$stmt = $conn->prepare(
    "SELECT id FROM patients WHERE user_id = ?"
);
$stmt->bind_param("i", $userId);
$stmt->execute();

$result = $stmt->get_result();
$patient = $result->fetch_assoc();

$patientId = $patient["id"];

$countStmt = $conn->prepare(
    "SELECT COUNT(*) AS total FROM appointments WHERE patient_id = ?"
);
$countStmt->bind_param("i", $patientId);
$countStmt->execute();

$countResult = $countStmt->get_result();
$count = $countResult->fetch_assoc()["total"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Dashboard - MediCare Clinic</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

<header>
    <div class="logo">MediCare Clinic</div>

    <nav>
        <a href="dashboard.php">Dashboard</a>
        <a href="profile.php">Profile</a>
        <a href="appointments.php">Appointments</a>
        <a href="book.php">Book Appointment</a>
        <a href="../logout.php">Logout</a>
    </nav>
</header>

<div class="dashboard">

    <h1>Welcome, <?php echo htmlspecialchars($name); ?>!</h1>

    <p>Manage your appointments and patient information from here.</p>

    <div class="dashboard-cards">

        <div class="dashboard-card">
            <h3>Total Appointments</h3>
            <p><?php echo $count; ?></p>
        </div>

        <div class="dashboard-card">
            <h3>Book Appointment</h3>
            <a href="book.php" class="btn">Book Now</a>
        </div>

        <div class="dashboard-card">
            <h3>My Profile</h3>
            <a href="profile.php" class="btn">View Profile</a>
        </div>

    </div>

</div>

<footer>
    <p>&copy; 2026 MediCare Clinic. All Rights Reserved.</p>
</footer>
<script src="../js/script.js"></script>
</body>
</html>