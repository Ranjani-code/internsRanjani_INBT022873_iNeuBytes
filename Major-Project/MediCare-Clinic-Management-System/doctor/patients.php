<?php
session_start();
include "../config/database.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "doctor") {
    header("Location: ../login.php");
    exit();
}

$userId = $_SESSION["user_id"];

$stmt = $conn->prepare("
    SELECT id
    FROM doctors
    WHERE email = (
        SELECT email FROM users WHERE id = ?
    )
");

$stmt->bind_param("i", $userId);
$stmt->execute();

$result = $stmt->get_result();
$doctor = $result->fetch_assoc();
$doctorId = $doctor["id"];

$stmt = $conn->prepare("
    SELECT DISTINCT
        u.name,
        u.email,
        p.phone,
        p.address,
        p.date_of_birth
    FROM patients p
    JOIN users u ON p.user_id = u.id
    JOIN appointments a ON p.id = a.patient_id
    WHERE a.doctor_id = ?
    ORDER BY u.name
");

$stmt->bind_param("i", $doctorId);
$stmt->execute();

$patients = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patients - MediCare Clinic</title>
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

    <h1>My Patients</h1>

    <div class="table-container">

        <table>

            <tr>
                <th>Patient Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Address</th>
                <th>Date of Birth</th>
            </tr>

            <?php if ($patients->num_rows > 0): ?>

                <?php while ($patient = $patients->fetch_assoc()): ?>

                    <tr>

                        <td>
                            <?php echo htmlspecialchars($patient["name"]); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($patient["email"]); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($patient["phone"]); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($patient["address"]); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($patient["date_of_birth"]); ?>
                        </td>

                    </tr>

                <?php endwhile; ?>

            <?php else: ?>

                <tr>
                    <td colspan="5">No patients found.</td>
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