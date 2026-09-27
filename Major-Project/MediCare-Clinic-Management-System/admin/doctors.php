<?php
session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../login.php");
    exit();
}

include "../config/database.php";

$message = "";

/* Add Doctor */
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["add_doctor"])) {

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
            INSERT INTO doctors
            (name, department, specialization, phone, email)
            VALUES (?, ?, ?, ?, ?)
        ");

        $stmt->bind_param(
            "sssss",
            $name,
            $department,
            $specialization,
            $phone,
            $email
        );

        if ($stmt->execute()) {
            $message = "Doctor added successfully.";
        }

        $stmt->close();
    }
}

/* Delete Doctor */
if (isset($_GET["delete"])) {

    $id = (int) $_GET["delete"];

    $stmt = $conn->prepare(
        "DELETE FROM doctors WHERE id = ?"
    );

    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    header("Location: doctors.php");
    exit();
}

/* Departments */
$department_result = $conn->query("
    SELECT name
    FROM departments
    ORDER BY name ASC
");

/* Doctors */
$doctor_result = $conn->query("
    SELECT
        id,
        name,
        department,
        specialization,
        phone,
        email
    FROM doctors
    ORDER BY id ASC
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

    <title>Doctors - MediCare Clinic</title>

    <link
        rel="stylesheet"
        href="../css/style.css"
    >

    <style>

        /* ================================
           DOCTOR MANAGEMENT PAGE
           ================================ */

        .doctor-page {
            background: #f5f8fa;
            padding: 50px 0 80px;
            min-height: calc(100vh - 160px);
        }

        .doctor-title {
            margin-bottom: 30px;
        }

        .doctor-title h1 {
            margin: 0 0 8px;
            color: #155b7a;
            font-size: 2.4rem;
        }

        .doctor-title p {
            margin: 0;
            color: #555;
            font-size: 0.95rem;
        }


        /* Success message */

        .doctor-message {
            margin-bottom: 25px;
            padding: 13px 18px;

            border: 1px solid #b7ddc8;
            border-radius: 8px;

            background: #effaf3;
            color: #236b42;

            font-size: 0.9rem;
            font-weight: 600;
        }


        /* Add Doctor Card */

        .add-doctor-card {
            width: 100%;
            max-width: 900px;

            margin-bottom: 35px;
            padding: 32px;

            background: #ffffff;

            border: 1px solid #e1e7eb;
            border-radius: 12px;

            box-shadow: 0 5px 18px rgba(0, 0, 0, 0.06);

            box-sizing: border-box;
        }

        .add-doctor-card h2 {
            margin: 0 0 25px;

            color: #155b7a;

            font-size: 1.45rem;
        }


        /* Form */

        .doctor-form-grid {
            display: grid;

            grid-template-columns: 1fr 1fr;

            gap: 20px 24px;

            margin-bottom: 25px;
        }

        .doctor-field {
            display: flex;
            flex-direction: column;
        }

        .doctor-field label {
            margin-bottom: 7px;

            color: #333;

            font-size: 0.86rem;
            font-weight: 600;
        }

        .doctor-field input,
        .doctor-field select {
            width: 100%;
            height: 44px;

            padding: 0 13px;

            box-sizing: border-box;

            border: 1px solid #d4dde2;
            border-radius: 7px;

            background: #ffffff;
            color: #222;

            font-family: inherit;
            font-size: 0.9rem;

            outline: none;
        }

        .doctor-field input:focus,
        .doctor-field select:focus {
            border-color: #11759d;

            box-shadow:
                0 0 0 3px rgba(17, 117, 157, 0.08);
        }


        /* Add button */

        .add-doctor-button {
            display: inline-flex;

            align-items: center;
            justify-content: center;

            height: 44px;

            padding: 0 24px;

            border: none;
            border-radius: 7px;

            background: #11759d;
            color: #ffffff;

            font-family: inherit;
            font-size: 0.9rem;
            font-weight: 600;

            cursor: pointer;
        }

        .add-doctor-button:hover {
            background: #0d607f;
        }


        /* Doctor table card */

        .doctor-table-card {
            width: 100%;

            padding: 28px;

            background: #ffffff;

            border: 1px solid #e1e7eb;
            border-radius: 12px;

            box-shadow: 0 5px 18px rgba(0, 0, 0, 0.06);

            box-sizing: border-box;

            overflow-x: auto;
        }

        .doctor-table-header {
            margin-bottom: 20px;
        }

        .doctor-table-header h2 {
            margin: 0 0 6px;

            color: #155b7a;

            font-size: 1.45rem;
        }

        .doctor-table-header p {
            margin: 0;

            color: #666;

            font-size: 0.88rem;
        }


        /* Table */

        .doctor-table {
            width: 100%;

            min-width: 950px;

            border-collapse: collapse;
        }

        .doctor-table th {
            padding: 14px 15px;

            background: #11759d;
            color: #ffffff;

            font-size: 0.84rem;
            font-weight: 600;

            text-align: left;

            white-space: nowrap;
        }

        .doctor-table th:first-child {
            border-radius: 6px 0 0 6px;
        }

        .doctor-table th:last-child {
            border-radius: 0 6px 6px 0;
        }

        .doctor-table td {
            padding: 15px;

            border-bottom: 1px solid #e5eaed;

            color: #333;

            font-size: 0.86rem;

            vertical-align: middle;
        }

        .doctor-table tbody tr:hover {
            background: #f8fbfc;
        }


        /* Action buttons */

        .doctor-actions {
            display: flex;

            align-items: center;

            gap: 8px;

            white-space: nowrap;
        }

        .edit-doctor {
            display: inline-flex;

            align-items: center;
            justify-content: center;

            height: 38px;

            padding: 0 14px;

            border-radius: 6px;

            background: #11759d;
            color: #ffffff;

            text-decoration: none;

            font-size: 0.8rem;
            font-weight: 600;
        }

        .edit-doctor:hover {
            background: #0d607f;
        }

        .delete-doctor {
            display: inline-flex;

            align-items: center;
            justify-content: center;

            height: 38px;

            padding: 0 14px;

            border: 1px solid #e0b5b2;
            border-radius: 6px;

            background: #fff5f4;
            color: #b3332b;

            text-decoration: none;

            font-size: 0.8rem;
            font-weight: 600;
        }

        .delete-doctor:hover {
            background: #fde9e7;
        }


        /* Tablet */

        @media (max-width: 850px) {

            .doctor-form-grid {
                grid-template-columns: 1fr;
            }

            .add-doctor-card {
                max-width: 100%;
            }

        }


        /* Mobile */

        @media (max-width: 600px) {

            .doctor-page {
                padding: 40px 0 60px;
            }

            .doctor-title h1 {
                font-size: 2rem;
            }

            .add-doctor-card,
            .doctor-table-card {
                padding: 22px 18px;
            }

            .add-doctor-button {
                width: 100%;
            }

        }
        /* FINAL CENTER ALIGNMENT */

.doctor-title {
    text-align: center;
}

.doctor-title p {
    text-align: center;
}

.add-doctor-card {
    margin-left: auto;
    margin-right: auto;
}

.doctor-table-card {
    margin-left: auto;
    margin-right: auto;
}

    </style>

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


<section class="doctor-page">

    <div class="container">


        <!-- Page Heading -->

        <div class="doctor-title">

            <h1>
                Doctor Management
            </h1>

            <p>
                Add and manage doctors at MediCare Clinic.
            </p>

        </div>


        <?php if ($message !== "") { ?>

            <div class="doctor-message">
                <?php echo htmlspecialchars($message); ?>
            </div>

        <?php } ?>


        <!-- Add Doctor -->

        <div class="add-doctor-card">

            <h2>
                Add Doctor
            </h2>

            <form method="POST">

                <div class="doctor-form-grid">


                    <div class="doctor-field">

                        <label for="name">
                            Doctor Name
                        </label>

                        <input
                            type="text"
                            id="name"
                            name="name"
                            placeholder="Enter doctor name"
                            required
                        >

                    </div>


                    <div class="doctor-field">

                        <label for="department">
                            Department
                        </label>

                        <select
                            id="department"
                            name="department"
                            required
                        >

                            <option value="">
                                Select Department
                            </option>

                            <?php while (
                                $department =
                                $department_result->fetch_assoc()
                            ) { ?>

                                <option
                                    value="<?php
                                    echo htmlspecialchars(
                                        $department["name"]
                                    );
                                    ?>"
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


                    <div class="doctor-field">

                        <label for="specialization">
                            Specialization
                        </label>

                        <input
                            type="text"
                            id="specialization"
                            name="specialization"
                            placeholder="e.g. Cardiologist"
                            required
                        >

                    </div>


                    <div class="doctor-field">

                        <label for="phone">
                            Phone
                        </label>

                        <input
                            type="text"
                            id="phone"
                            name="phone"
                            placeholder="Enter phone number"
                            required
                        >

                    </div>


                    <div class="doctor-field">

                        <label for="email">
                            Email Address
                        </label>

                        <input
                            type="email"
                            id="email"
                            name="email"
                            placeholder="Enter email address"
                            required
                        >

                    </div>


                </div>


                <button
                    type="submit"
                    name="add_doctor"
                    class="add-doctor-button"
                >
                    Add Doctor
                </button>

            </form>

        </div>


        <!-- Doctors Table -->

        <div class="doctor-table-card">

            <div class="doctor-table-header">

                <h2>
                    Doctors
                </h2>

                <p>
                    View and manage registered doctors.
                </p>

            </div>


            <table class="doctor-table">

                <thead>

                    <tr>

                        <th>ID</th>
                        <th>Name</th>
                        <th>Department</th>
                        <th>Specialization</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Actions</th>

                    </tr>

                </thead>


                <tbody>

                    <?php while (
                        $doctor =
                        $doctor_result->fetch_assoc()
                    ) { ?>

                        <tr>

                            <td>
                                <?php
                                echo $doctor["id"];
                                ?>
                            </td>

                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $doctor["name"]
                                );
                                ?>
                            </td>

                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $doctor["department"]
                                );
                                ?>
                            </td>

                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $doctor["specialization"]
                                );
                                ?>
                            </td>

                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $doctor["phone"]
                                );
                                ?>
                            </td>

                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $doctor["email"]
                                );
                                ?>
                            </td>

                            <td>

                                <div class="doctor-actions">

                                    <a
                                        href="doctor-profile.php?id=<?php echo $doctor["id"]; ?>"
                                        class="edit-doctor"
                                    >
                                        Edit Profile
                                    </a>

                                    <a
                                        href="doctors.php?delete=<?php echo $doctor["id"]; ?>"
                                        class="delete-doctor"
                                    >
                                        Delete
                                    </a>

                                </div>

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