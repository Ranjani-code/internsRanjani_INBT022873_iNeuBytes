<?php
session_start();
include "../config/database.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "patient") {
    header("Location: ../login.php");
    exit();
}

$userId = $_SESSION["user_id"];
$message = "";

$stmt = $conn->prepare("SELECT id FROM patients WHERE user_id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();

$result = $stmt->get_result();
$patient = $result->fetch_assoc();
$patientId = $patient["id"];

$doctors = $conn->query("SELECT id, name, department FROM doctors");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $doctorId = $_POST["doctor_id"];
    $date = $_POST["appointment_date"];
    $time = $_POST["appointment_time"];
    $reason = trim($_POST["reason"]);

    if ($doctorId == "" || $date == "" || $time == "") {
        $message = "Please fill in all required fields.";
    } else {

        $stmt = $conn->prepare("
            INSERT INTO appointments
            (patient_id, doctor_id, appointment_date, appointment_time, reason)
            VALUES (?, ?, ?, ?, ?)
        ");

        $stmt->bind_param(
            "iisss",
            $patientId,
            $doctorId,
            $date,
            $time,
            $reason
        );

        if ($stmt->execute()) {
            $message = "Appointment booked successfully.";
        } else {
            $message = "Unable to book appointment.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Appointment - MediCare Clinic</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

<header>
    <div class="logo">MediCare Clinic</div>

    <nav>
        <a href="dashboard.php">Dashboard</a>
        <a href="profile.php">Profile</a>
        <a href="appointments.php">Appointments</a>
        <a href="../logout.php">Logout</a>
    </nav>
</header>

<div class="form-container">

    <h2>Book an Appointment</h2>

    <?php if ($message != ""): ?>
        <p class="message"><?php echo $message; ?></p>
    <?php endif; ?>

    <form method="POST">

        <label>Select Doctor</label>

        <select name="doctor_id" required>
            <option value="">Select Doctor</option>

            <?php while ($doctor = $doctors->fetch_assoc()): ?>

                <option value="<?php echo $doctor["id"]; ?>">
                    <?php
                    echo htmlspecialchars(
                        $doctor["name"] . " - " . $doctor["department"]
                    );
                    ?>
                </option>

            <?php endwhile; ?>

        </select>

        <label>Appointment Date</label>
        <input type="date" name="appointment_date" required>

        <label>Appointment Time</label>
        <input type="time" name="appointment_time" required>

        <label>Reason for Visit</label>
        <textarea name="reason" placeholder="Enter your reason for the visit"></textarea>

        <button type="submit" class="btn">Book Appointment</button>

    </form>

</div>

<footer>
    <p>&copy; 2026 MediCare Clinic. All Rights Reserved.</p>
</footer>
<script src="../js/script.js"></script>
</body>
</html>