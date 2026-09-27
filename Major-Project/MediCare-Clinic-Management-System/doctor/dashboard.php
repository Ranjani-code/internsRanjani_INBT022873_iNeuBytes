<?php
session_start();
include "../config/database.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "doctor") {
    header("Location: ../login.php");
    exit();
}

$doctorId = $_SESSION["user_id"];

$stmt = $conn->prepare("
    SELECT id, name, department
    FROM doctors
    WHERE email = (
        SELECT email FROM users WHERE id = ?
    )
");

$stmt->bind_param("i", $doctorId);
$stmt->execute();

$doctorResult = $stmt->get_result();
$doctor = $doctorResult->fetch_assoc();

$doctorDbId = $doctor["id"];

$stmt = $conn->prepare("
    SELECT COUNT(*) AS total
    FROM appointments
    WHERE doctor_id = ?
");

$stmt->bind_param("i", $doctorDbId);
$stmt->execute();

$countResult = $stmt->get_result();
$totalAppointments = $countResult->fetch_assoc()["total"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Dashboard - MediCare Clinic</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

<header>
    <div class="logo">MediCare Clinic</div>

    <nav>
        <a href="dashboard.php">Dashboard</a>
        <a href="appointments.php">Appointments</a>
        <a href="patients.php">Patients</a>
        <a href="../logout.php">Logout</a>
    </nav>
</header>

<div class="dashboard">

    <h1>Welcome, <?php echo htmlspecialchars($doctor["name"]); ?>!</h1>

    <p>
        Department:
        <?php echo htmlspecialchars($doctor["department"]); ?>
    </p>

    <div class="dashboard-cards">

        <div class="dashboard-card">
            <h3>Total Appointments</h3>
            <p><?php echo $totalAppointments; ?></p>
        </div>

        <div class="dashboard-card">
            <h3>Appointments</h3>
            <a href="appointments.php" class="btn">View Appointments</a>
        </div>

        <div class="dashboard-card">
            <h3>Patients</h3>
            <a href="patients.php" class="btn">View Patients</a>
        </div>

    </div>

</div>

<footer>
    <p>&copy; 2026 MediCare Clinic. All Rights Reserved.</p>
</footer>
<script src="../js/script.js"></script>
</body>
</html>