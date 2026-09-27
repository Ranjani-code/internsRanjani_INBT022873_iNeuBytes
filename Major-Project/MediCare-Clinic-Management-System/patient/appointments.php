<?php
session_start();
include "../config/database.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "patient") {
    header("Location: ../login.php");
    exit();
}

$userId = $_SESSION["user_id"];

$stmt = $conn->prepare("SELECT id FROM patients WHERE user_id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();

$result = $stmt->get_result();
$patient = $result->fetch_assoc();
$patientId = $patient["id"];

$stmt = $conn->prepare("
    SELECT a.id, d.name AS doctor_name, d.department,
           a.appointment_date, a.appointment_time,
           a.reason, a.status
    FROM appointments a
    JOIN doctors d ON a.doctor_id = d.id
    WHERE a.patient_id = ?
    ORDER BY a.appointment_date DESC
");

$stmt->bind_param("i", $patientId);
$stmt->execute();
$appointments = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Appointments - MediCare Clinic</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

<header>
    <div class="logo">MediCare Clinic</div>

    <nav>
        <a href="dashboard.php">Dashboard</a>
        <a href="profile.php">Profile</a>
        <a href="book.php">Book Appointment</a>
        <a href="../logout.php">Logout</a>
    </nav>
</header>

<div class="dashboard">

    <h1>My Appointments</h1>

    <a href="book.php" class="btn">Book New Appointment</a>

    <div class="table-container">

        <table>
            <tr>
                <th>Doctor</th>
                <th>Department</th>
                <th>Date</th>
                <th>Time</th>
                <th>Reason</th>
                <th>Status</th>
            </tr>

            <?php if ($appointments->num_rows > 0): ?>

                <?php while ($appointment = $appointments->fetch_assoc()): ?>

                    <tr>
                        <td><?php echo htmlspecialchars($appointment["doctor_name"]); ?></td>

                        <td><?php echo htmlspecialchars($appointment["department"]); ?></td>

                        <td><?php echo htmlspecialchars($appointment["appointment_date"]); ?></td>

                        <td><?php echo htmlspecialchars($appointment["appointment_time"]); ?></td>

                        <td><?php echo htmlspecialchars($appointment["reason"]); ?></td>

                        <td><?php echo htmlspecialchars($appointment["status"]); ?></td>
                    </tr>

                <?php endwhile; ?>

            <?php else: ?>

                <tr>
                    <td colspan="6">No appointments found.</td>
                </tr>

            <?php endif; ?>

        </table>

    </div>

</div>

<footer>
    <p>&copy; 2026 MediCare Clinic. All Rights Reserved.</p>
</footer>
<script src="../js/script.js"></script>
</body>
</html>