<?php

session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "doctor") {
    header("Location: ../login.php");
    exit();
}

include "../config/database.php";

$user_id = $_SESSION["user_id"];


/* Show success message after an actual update */
$message = "";

if (isset($_SESSION["appointment_message"])) {
    $message = $_SESSION["appointment_message"];
    unset($_SESSION["appointment_message"]);
}


/* Find logged-in doctor */
$stmt = $conn->prepare("
    SELECT doctors.id, doctors.name, doctors.department
    FROM doctors
    JOIN users ON doctors.email = users.email
    WHERE users.id = ?
");

$stmt->bind_param("i", $user_id);
$stmt->execute();

$doctor_result = $stmt->get_result();

if ($doctor_result->num_rows === 0) {
    die("Doctor account not found.");
}

$doctor = $doctor_result->fetch_assoc();

$doctor_id = $doctor["id"];

$stmt->close();


/* Update appointment status */
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["update_status"])) {

    $appointment_id = $_POST["appointment_id"];
    $new_status = $_POST["status"];


    /* Get current status */
    $stmt = $conn->prepare("
        SELECT status
        FROM appointments
        WHERE id = ? AND doctor_id = ?
    ");

    $stmt->bind_param(
        "ii",
        $appointment_id,
        $doctor_id
    );

    $stmt->execute();

    $current_result = $stmt->get_result();

    if ($current_result->num_rows === 0) {
        $stmt->close();
        header("Location: appointments.php");
        exit();
    }

    $current = $current_result->fetch_assoc();

    $old_status = $current["status"];

    $stmt->close();


    /* Update only if status actually changed */
    if ($old_status !== $new_status) {

        $stmt = $conn->prepare("
            UPDATE appointments
            SET status = ?,
                updated_by = ?,
                updated_at = NOW()
            WHERE id = ? AND doctor_id = ?
        ");

        $stmt->bind_param(
            "ssii",
            $new_status,
            $doctor["name"],
            $appointment_id,
            $doctor_id
        );

        $stmt->execute();

        $stmt->close();

        $_SESSION["appointment_message"] =
            "Appointment status updated successfully.";
    }


    /* Return to appointment page */
    header("Location: appointments.php");
    exit();
}


/* Get doctor's appointments */
$stmt = $conn->prepare("
    SELECT
        appointments.id,
        users.name AS patient_name,
        appointments.appointment_date,
        appointments.appointment_time,
        appointments.reason,
        appointments.status
    FROM appointments
    JOIN patients
        ON appointments.patient_id = patients.id
    JOIN users
        ON patients.user_id = users.id
    WHERE appointments.doctor_id = ?
    ORDER BY appointments.appointment_date DESC
");

$stmt->bind_param("i", $doctor_id);

$stmt->execute();

$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Doctor Appointments - MediCare Clinic</title>

    <link rel="stylesheet" href="../css/style.css">

</head>

<body>


<header>

    <div class="container">

        <h2>MediCare Clinic</h2>

        <nav>

            <a href="../index.php">Home</a>

            <a href="dashboard.php">Dashboard</a>

            <a href="appointments.php">Appointments</a>

            <a href="patients.php">Patients</a>

            <a href="../logout.php">Logout</a>

        </nav>

    </div>

</header>


<section class="dashboard">

    <div class="container">

        <h1>My Appointments</h1>

        <p>
            Doctor:
            <strong>
                <?php echo htmlspecialchars($doctor["name"]); ?>
            </strong>
        </p>


        <?php if ($message != "") { ?>

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
                        <th>Date</th>
                        <th>Time</th>
                        <th>Reason</th>
                        <th>Status</th>

                    </tr>

                </thead>


                <tbody>

                <?php if ($result->num_rows > 0) { ?>

                    <?php while ($row = $result->fetch_assoc()) { ?>

                        <tr>

                            <td>
                                <?php echo $row["id"]; ?>
                            </td>

                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $row["patient_name"]
                                );
                                ?>
                            </td>

                            <td>
                                <?php echo $row["appointment_date"]; ?>
                            </td>

                            <td>
                                <?php echo $row["appointment_time"]; ?>
                            </td>

                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $row["reason"]
                                );
                                ?>
                            </td>

                            <td>

                                <form method="POST">

                                    <input
                                        type="hidden"
                                        name="appointment_id"
                                        value="<?php
                                        echo $row["id"];
                                        ?>"
                                    >

                                    <select name="status">

                                        <option
                                            value="Pending"
                                            <?php
                                            if (
                                                $row["status"]
                                                == "Pending"
                                            ) {
                                                echo "selected";
                                            }
                                            ?>
                                        >
                                            Pending
                                        </option>

                                        <option
                                            value="Confirmed"
                                            <?php
                                            if (
                                                $row["status"]
                                                == "Confirmed"
                                            ) {
                                                echo "selected";
                                            }
                                            ?>
                                        >
                                            Confirmed
                                        </option>

                                        <option
                                            value="Completed"
                                            <?php
                                            if (
                                                $row["status"]
                                                == "Completed"
                                            ) {
                                                echo "selected";
                                            }
                                            ?>
                                        >
                                            Completed
                                        </option>

                                        <option
                                            value="Cancelled"
                                            <?php
                                            if (
                                                $row["status"]
                                                == "Cancelled"
                                            ) {
                                                echo "selected";
                                            }
                                            ?>
                                        >
                                            Cancelled
                                        </option>

                                    </select>

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

                <?php } else { ?>

                    <tr>

                        <td colspan="6">
                            No appointments found.
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