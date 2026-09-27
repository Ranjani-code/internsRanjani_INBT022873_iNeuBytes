<?php
session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../login.php");
    exit();
}

include "../config/database.php";

$message = "";

if (isset($_SESSION["appointment_message"])) {
    $message = $_SESSION["appointment_message"];
    unset($_SESSION["appointment_message"]);
}


/* Update appointment status */

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["update_status"])) {

    $appointment_id = (int) $_POST["appointment_id"];
    $new_status = $_POST["status"];

    $stmt = $conn->prepare("
        SELECT status
        FROM appointments
        WHERE id = ?
    ");

    $stmt->bind_param("i", $appointment_id);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {

        $appointment = $result->fetch_assoc();
        $old_status = $appointment["status"];

        $stmt->close();

        if ($old_status !== $new_status) {

            $stmt = $conn->prepare("
                UPDATE appointments
                SET status = ?,
                    updated_by = ?,
                    updated_at = NOW()
                WHERE id = ?
            ");

            $updated_by = $_SESSION["name"];

            $stmt->bind_param(
                "ssi",
                $new_status,
                $updated_by,
                $appointment_id
            );

            $stmt->execute();
            $stmt->close();

            $_SESSION["appointment_message"] =
                "Appointment status updated successfully.";
        }
    }

    header("Location: appointments.php");
    exit();
}


/* Get appointments */

$result = $conn->query("
    SELECT
        appointments.id,
        users.name AS patient_name,
        doctors.name AS doctor_name,
        doctors.department,
        appointments.appointment_date,
        appointments.appointment_time,
        appointments.reason,
        appointments.status,
        appointments.updated_by,
        appointments.updated_at
    FROM appointments
    JOIN patients
        ON appointments.patient_id = patients.id
    JOIN users
        ON patients.user_id = users.id
    JOIN doctors
        ON appointments.doctor_id = doctors.id
    ORDER BY appointments.appointment_date DESC
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

    <title>Appointments - MediCare Clinic</title>

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

        <h1>Appointment Management</h1>


        <?php if ($message !== "") { ?>

            <p class="success-message">
                <?php echo htmlspecialchars($message); ?>
            </p>

        <?php } ?>


        <div class="table-container">

            <table>

                <thead>

                    <tr>

                        <th>ID</th>
                        <th>Patient</th>
                        <th>Doctor</th>
                        <th>Department</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Reason</th>
                        <th>Status</th>
                        <th>Updated By</th>
                        <th>Updated At</th>
                        <th>Action</th>

                    </tr>

                </thead>

                <tbody>

                    <?php while ($appointment = $result->fetch_assoc()) { ?>

                        <tr>

                            <td>
                                <?php echo $appointment["id"]; ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($appointment["patient_name"]); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($appointment["doctor_name"]); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($appointment["department"]); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($appointment["appointment_date"]); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($appointment["appointment_time"]); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($appointment["reason"]); ?>
                            </td>

                            <td>

                                <form method="POST">

                                    <input
                                        type="hidden"
                                        name="appointment_id"
                                        value="<?php echo $appointment["id"]; ?>"
                                    >

                                    <select name="status">

                                        <option
                                            value="Pending"
                                            <?php
                                            if ($appointment["status"] === "Pending") {
                                                echo "selected";
                                            }
                                            ?>
                                        >
                                            Pending
                                        </option>

                                        <option
                                            value="Confirmed"
                                            <?php
                                            if ($appointment["status"] === "Confirmed") {
                                                echo "selected";
                                            }
                                            ?>
                                        >
                                            Confirmed
                                        </option>

                                        <option
                                            value="Completed"
                                            <?php
                                            if ($appointment["status"] === "Completed") {
                                                echo "selected";
                                            }
                                            ?>
                                        >
                                            Completed
                                        </option>

                                        <option
                                            value="Cancelled"
                                            <?php
                                            if ($appointment["status"] === "Cancelled") {
                                                echo "selected";
                                            }
                                            ?>
                                        >
                                            Cancelled
                                        </option>

                                    </select>

                            </td>

                            <td>
                                <?php
                                echo $appointment["updated_by"]
                                    ? htmlspecialchars($appointment["updated_by"])
                                    : "-";
                                ?>
                            </td>

                            <td>
                                <?php
                                echo $appointment["updated_at"]
                                    ? htmlspecialchars($appointment["updated_at"])
                                    : "-";
                                ?>
                            </td>

                            <td>

                                    <button
                                        type="submit"
                                        name="update_status"
                                        class="btn"
                                    >
                                        Update
                                    </button>

                                </form>

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