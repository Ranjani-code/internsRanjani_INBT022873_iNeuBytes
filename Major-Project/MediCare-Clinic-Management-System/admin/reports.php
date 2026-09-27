<?php
session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../login.php");
    exit();
}

include "../config/database.php";


$patients = $conn->query(
    "SELECT COUNT(*) AS total FROM patients"
)->fetch_assoc()["total"];

$doctors = $conn->query(
    "SELECT COUNT(*) AS total FROM doctors"
)->fetch_assoc()["total"];

$departments = $conn->query(
    "SELECT COUNT(*) AS total FROM departments"
)->fetch_assoc()["total"];

$appointments = $conn->query(
    "SELECT COUNT(*) AS total FROM appointments"
)->fetch_assoc()["total"];


$pending = $conn->query(
    "SELECT COUNT(*) AS total
     FROM appointments
     WHERE status = 'Pending'"
)->fetch_assoc()["total"];

$confirmed = $conn->query(
    "SELECT COUNT(*) AS total
     FROM appointments
     WHERE status = 'Confirmed'"
)->fetch_assoc()["total"];

$completed = $conn->query(
    "SELECT COUNT(*) AS total
     FROM appointments
     WHERE status = 'Completed'"
)->fetch_assoc()["total"];

$cancelled = $conn->query(
    "SELECT COUNT(*) AS total
     FROM appointments
     WHERE status = 'Cancelled'"
)->fetch_assoc()["total"];


$department_result = $conn->query("
    SELECT
        doctors.department,
        COUNT(appointments.id) AS total
    FROM doctors
    LEFT JOIN appointments
        ON doctors.id = appointments.doctor_id
    GROUP BY doctors.department
    ORDER BY total DESC
");


$doctor_result = $conn->query("
    SELECT
        doctors.name,
        doctors.department,
        COUNT(appointments.id) AS total
    FROM doctors
    LEFT JOIN appointments
        ON doctors.id = appointments.doctor_id
    GROUP BY doctors.id
    ORDER BY total DESC
");

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Reports - MediCare Clinic</title>

    <link
        rel="stylesheet"
        href="../css/style.css"
    >

</head>

<body>

<header>

    <div class="container">

        <div class="logo">
            MediCare Clinic
        </div>

        <nav>

            <a href="dashboard.php">Dashboard</a>
            <a href="doctors.php">Doctors</a>
            <a href="patients.php">Patients</a>
            <a href="departments.php">Departments</a>
            <a href="appointments.php">Appointments</a>
            <a href="reports.php">Reports</a>
            <a href="../logout.php">Logout</a>

        </nav>

    </div>

</header>


<section class="dashboard">

    <div class="container">

        <h1>Reports & Analytics</h1>


        <div class="dashboard-cards">

            <div class="dashboard-card">
                <h3>Total Patients</h3>
                <strong><?php echo $patients; ?></strong>
            </div>

            <div class="dashboard-card">
                <h3>Total Doctors</h3>
                <strong><?php echo $doctors; ?></strong>
            </div>

            <div class="dashboard-card">
                <h3>Total Departments</h3>
                <strong><?php echo $departments; ?></strong>
            </div>

            <div class="dashboard-card">
                <h3>Total Appointments</h3>
                <strong><?php echo $appointments; ?></strong>
            </div>

        </div>


        <div class="table-container">

            <h2>Appointment Status</h2>

            <table>

                <thead>

                    <tr>
                        <th>Status</th>
                        <th>Total</th>
                    </tr>

                </thead>

                <tbody>

                    <tr>
                        <td>Pending</td>
                        <td><?php echo $pending; ?></td>
                    </tr>

                    <tr>
                        <td>Confirmed</td>
                        <td><?php echo $confirmed; ?></td>
                    </tr>

                    <tr>
                        <td>Completed</td>
                        <td><?php echo $completed; ?></td>
                    </tr>

                    <tr>
                        <td>Cancelled</td>
                        <td><?php echo $cancelled; ?></td>
                    </tr>

                </tbody>

            </table>

        </div>


        <div class="table-container">

            <h2>Department-wise Appointments</h2>

            <table>

                <thead>

                    <tr>
                        <th>Department</th>
                        <th>Appointments</th>
                    </tr>

                </thead>

                <tbody>

                    <?php while ($row = $department_result->fetch_assoc()) { ?>

                        <tr>

                            <td>
                                <?php echo htmlspecialchars($row["department"]); ?>
                            </td>

                            <td>
                                <?php echo $row["total"]; ?>
                            </td>

                        </tr>

                    <?php } ?>

                </tbody>

            </table>

        </div>


        <div class="table-container">

            <h2>Doctor-wise Appointments</h2>

            <table>

                <thead>

                    <tr>
                        <th>Doctor</th>
                        <th>Department</th>
                        <th>Appointments</th>
                    </tr>

                </thead>

                <tbody>

                    <?php while ($row = $doctor_result->fetch_assoc()) { ?>

                        <tr>

                            <td>
                                <?php echo htmlspecialchars($row["name"]); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($row["department"]); ?>
                            </td>

                            <td>
                                <?php echo $row["total"]; ?>
                            </td>

                        </tr>

                    <?php } ?>

                </tbody>

            </table>

        </div>

    </div>

</section>


<footer>

    <p>
        &copy; 2026 MediCare Clinic. All rights reserved.
    </p>

</footer>


<script src="../js/script.js"></script>

</body>

</html>