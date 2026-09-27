<?php
session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../login.php");
    exit();
}

include "../config/database.php";


if (isset($_GET["delete"])) {

    $patient_id = (int) $_GET["delete"];

    $stmt = $conn->prepare("
        SELECT user_id
        FROM patients
        WHERE id = ?
    ");

    $stmt->bind_param("i", $patient_id);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {

        $patient = $result->fetch_assoc();
        $user_id = $patient["user_id"];

        $stmt->close();

        $stmt = $conn->prepare(
            "DELETE FROM patients WHERE id = ?"
        );

        $stmt->bind_param("i", $patient_id);
        $stmt->execute();
        $stmt->close();

        $stmt = $conn->prepare(
            "DELETE FROM users WHERE id = ?"
        );

        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->close();
    }

    header("Location: patients.php");
    exit();
}


$result = $conn->query("
    SELECT
        patients.id,
        users.name,
        users.email,
        patients.phone,
        patients.address,
        patients.date_of_birth
    FROM patients
    JOIN users
        ON patients.user_id = users.id
    ORDER BY users.name ASC
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

    <title>Patients - MediCare Clinic</title>

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

        <h1>Patient Management</h1>


        <div class="table-container">

            <table>

                <thead>

                    <tr>

                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Address</th>
                        <th>Date of Birth</th>
                        <th>Action</th>

                    </tr>

                </thead>

                <tbody>

                    <?php while ($patient = $result->fetch_assoc()) { ?>

                        <tr>

                            <td>
                                <?php echo $patient["id"]; ?>
                            </td>

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

                            <td>

                                <a
                                    href="patients.php?delete=<?php echo $patient["id"]; ?>"
                                    class="delete-btn"
                                >
                                    Delete
                                </a>

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