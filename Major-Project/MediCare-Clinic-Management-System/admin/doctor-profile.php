<?php
session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../login.php");
    exit();
}

include "../config/database.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $id = (int) $_POST["id"];
    $name = trim($_POST["name"]);
    $department = trim($_POST["department"]);
    $specialization = trim($_POST["specialization"]);
    $phone = trim($_POST["phone"]);
    $email = trim($_POST["email"]);

    if (
        $name !== "" &&
        $department !== "" &&
        $specialization !== "" &&
        $phone !== "" &&
        $email !== ""
    ) {

        $stmt = $conn->prepare("
            UPDATE doctors
            SET name = ?,
                department = ?,
                specialization = ?,
                phone = ?,
                email = ?
            WHERE id = ?
        ");

        $stmt->bind_param(
            "sssssi",
            $name,
            $department,
            $specialization,
            $phone,
            $email,
            $id
        );

        $stmt->execute();
        $stmt->close();

        $message = "Doctor profile updated successfully.";
    }
}


/* Get doctor to edit */
if (!isset($_GET["id"])) {
    header("Location: doctors.php");
    exit();
}

$id = (int) $_GET["id"];

$stmt = $conn->prepare("
    SELECT id, name, department, specialization, phone, email
    FROM doctors
    WHERE id = ?
");

$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $stmt->close();
    die("Doctor not found.");
}

$doctor = $result->fetch_assoc();

$stmt->close();


/* Get departments */
$departments = $conn->query("
    SELECT name
    FROM departments
    ORDER BY name ASC
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

    <title>Doctor Profile - MediCare Clinic</title>

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

        <h1>Doctor Profile Management</h1>

        <?php if ($message !== "") { ?>

            <p class="success-message">
                <?php echo htmlspecialchars($message); ?>
            </p>

        <?php } ?>


        <div class="form-card">

            <h2>Edit Doctor Profile</h2>

            <form method="POST">

                <input
                    type="hidden"
                    name="id"
                    value="<?php echo $doctor["id"]; ?>"
                >


                <div class="form-group">

                    <label>Doctor Name</label>

                    <input
                        type="text"
                        name="name"
                        value="<?php echo htmlspecialchars($doctor["name"]); ?>"
                        required
                    >

                </div>


                <div class="form-group">

                    <label>Department</label>

                    <select
                        name="department"
                        required
                    >

                        <?php while ($department = $departments->fetch_assoc()) { ?>

                            <option
                                value="<?php echo htmlspecialchars($department["name"]); ?>"
                                <?php
                                if (
                                    $department["name"] ===
                                    $doctor["department"]
                                ) {
                                    echo "selected";
                                }
                                ?>
                            >
                                <?php
                                echo htmlspecialchars(
                                    $department["name"]
                                );
                                ?>
                            </option>

                        <?php } ?>

                    </select>

                </div>


                <div class="form-group">

                    <label>Specialization</label>

                    <input
                        type="text"
                        name="specialization"
                        value="<?php echo htmlspecialchars($doctor["specialization"]); ?>"
                        required
                    >

                </div>


                <div class="form-group">

                    <label>Phone</label>

                    <input
                        type="text"
                        name="phone"
                        value="<?php echo htmlspecialchars($doctor["phone"]); ?>"
                        required
                    >

                </div>


                <div class="form-group">

                    <label>Email</label>

                    <input
                        type="email"
                        name="email"
                        value="<?php echo htmlspecialchars($doctor["email"]); ?>"
                        required
                    >

                </div>


                <button
                    type="submit"
                    class="btn"
                >
                    Save Changes
                </button>

                <a
                    href="doctors.php"
                    class="btn"
                >
                    Back to Doctors
                </a>

            </form>

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