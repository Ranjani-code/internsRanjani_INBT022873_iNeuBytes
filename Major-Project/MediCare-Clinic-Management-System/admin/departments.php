<?php
session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../login.php");
    exit();
}

include "../config/database.php";

$message = "";


/* Add Department */

if (
    $_SERVER["REQUEST_METHOD"] === "POST"
    && isset($_POST["add_department"])
) {

    $name = trim($_POST["name"]);

    if ($name !== "") {

        $check = $conn->prepare(
            "SELECT id FROM departments WHERE name = ?"
        );

        $check->bind_param("s", $name);
        $check->execute();

        $result = $check->get_result();

        if ($result->num_rows > 0) {

            $message = "Department already exists.";

        } else {

            $stmt = $conn->prepare(
                "INSERT INTO departments (name) VALUES (?)"
            );

            $stmt->bind_param("s", $name);
            $stmt->execute();
            $stmt->close();

            $message = "Department added successfully.";
        }

        $check->close();
    }
}


/* Delete Department */

if (isset($_GET["delete"])) {

    $id = (int) $_GET["delete"];

    $stmt = $conn->prepare(
        "DELETE FROM departments WHERE id = ?"
    );

    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    header("Location: departments.php");
    exit();
}


/* Get Departments */

$department_result = $conn->query("
    SELECT id, name
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

    <title>Departments - MediCare Clinic</title>

    <link
        rel="stylesheet"
        href="../css/style.css"
    >

    <style>

        /* =========================================
           DEPARTMENT MANAGEMENT
           ========================================= */

        .department-page {
            background: #f5f8fa;
            padding: 50px 0 80px;
            min-height: calc(100vh - 160px);
        }

        .department-title {
            text-align: center;
            margin-bottom: 35px;
        }

        .department-title h1 {
            margin: 0 0 10px;
            color: #155b7a;
            font-size: 2.4rem;
        }

        .department-title p {
            margin: 0;
            color: #555;
            font-size: 0.95rem;
        }


        /* Message */

        .department-message {
            max-width: 800px;

            margin: 0 auto 25px;

            padding: 13px 18px;

            border: 1px solid #b7ddc8;
            border-radius: 8px;

            background: #effaf3;
            color: #236b42;

            text-align: center;

            font-size: 0.9rem;
            font-weight: 600;
        }


        /* Add Department Card */

        .department-form-card {
            width: 100%;
            max-width: 800px;

            margin: 0 auto 40px;

            padding: 32px;

            box-sizing: border-box;

            background: #ffffff;

            border: 1px solid #e1e7eb;
            border-radius: 12px;

            box-shadow:
                0 5px 18px rgba(0, 0, 0, 0.06);
        }

        .department-form-card h2 {
            margin: 0 0 25px;

            color: #155b7a;

            text-align: center;

            font-size: 1.45rem;
        }


        /* Form */

        .department-form {
            display: flex;

            align-items: flex-end;

            gap: 15px;
        }

        .department-field {
            flex: 1;
        }

        .department-field label {
            display: block;

            margin-bottom: 8px;

            color: #333;

            font-size: 0.86rem;
            font-weight: 600;
        }

        .department-field input {
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

        .department-field input:focus {
            border-color: #11759d;

            box-shadow:
                0 0 0 3px rgba(17, 117, 157, 0.08);
        }


        /* Add button */

        .department-add-button {
            height: 44px;

            padding: 0 23px;

            border: none;
            border-radius: 7px;

            background: #11759d;
            color: #ffffff;

            font-family: inherit;

            font-size: 0.9rem;
            font-weight: 600;

            cursor: pointer;

            white-space: nowrap;
        }

        .department-add-button:hover {
            background: #0d607f;
        }


        /* Department Table */

        .department-table-card {
            width: 100%;

            max-width: 900px;

            margin: 0 auto;

            padding: 28px;

            box-sizing: border-box;

            background: #ffffff;

            border: 1px solid #e1e7eb;
            border-radius: 12px;

            box-shadow:
                0 5px 18px rgba(0, 0, 0, 0.06);

            overflow-x: auto;
        }

        .department-table-header {
            text-align: center;

            margin-bottom: 22px;
        }

        .department-table-header h2 {
            margin: 0 0 6px;

            color: #155b7a;

            font-size: 1.45rem;
        }

        .department-table-header p {
            margin: 0;

            color: #666;

            font-size: 0.88rem;
        }


        .department-table {
            width: 100%;

            border-collapse: collapse;

            min-width: 550px;
        }

        .department-table th {
            padding: 14px 16px;

            background: #11759d;

            color: #ffffff;

            font-size: 0.84rem;
            font-weight: 600;

            text-align: left;
        }

        .department-table th:first-child {
            border-radius: 6px 0 0 6px;
        }

        .department-table th:last-child {
            border-radius: 0 6px 6px 0;

            text-align: center;
        }

        .department-table td {
            padding: 15px 16px;

            border-bottom: 1px solid #e5eaed;

            color: #333;

            font-size: 0.88rem;

            vertical-align: middle;
        }

        .department-table td:last-child {
            text-align: center;
        }

        .department-table tbody tr:hover {
            background: #f8fbfc;
        }


        /* Delete */

        .department-delete {
            display: inline-flex;

            align-items: center;
            justify-content: center;

            height: 36px;

            padding: 0 14px;

            border: 1px solid #e0b5b2;
            border-radius: 6px;

            background: #fff5f4;

            color: #b3332b;

            text-decoration: none;

            font-size: 0.8rem;
            font-weight: 600;
        }

        .department-delete:hover {
            background: #fde9e7;
        }


        /* Mobile */

        @media (max-width: 650px) {

            .department-page {
                padding: 40px 0 60px;
            }

            .department-title h1 {
                font-size: 2rem;
            }

            .department-form-card,
            .department-table-card {
                padding: 22px 18px;
            }

            .department-form {
                flex-direction: column;
                align-items: stretch;
            }

            .department-add-button {
                width: 100%;
            }

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


<section class="department-page">

    <div class="container">


        <div class="department-title">

            <h1>
                Department Management
            </h1>

            <p>
                Manage the medical departments available at MediCare Clinic.
            </p>

        </div>


        <?php if ($message !== "") { ?>

            <div class="department-message">

                <?php
                echo htmlspecialchars($message);
                ?>

            </div>

        <?php } ?>


        <!-- Add Department -->

        <div class="department-form-card">

            <h2>
                Add Department
            </h2>

            <form
                method="POST"
                class="department-form"
            >

                <div class="department-field">

                    <label for="name">
                        Department Name
                    </label>

                    <input
                        type="text"
                        id="name"
                        name="name"
                        placeholder="Enter department name"
                        required
                    >

                </div>


                <button
                    type="submit"
                    name="add_department"
                    class="department-add-button"
                >
                    Add Department
                </button>

            </form>

        </div>


        <!-- Department Table -->

        <div class="department-table-card">

            <div class="department-table-header">

                <h2>
                    Medical Departments
                </h2>

                <p>
                    Departments currently available at the clinic.
                </p>

            </div>


            <table class="department-table">

                <thead>

                    <tr>

                        <th>
                            ID
                        </th>

                        <th>
                            Department
                        </th>

                        <th>
                            Action
                        </th>

                    </tr>

                </thead>


                <tbody>

                    <?php while (
                        $department =
                        $department_result->fetch_assoc()
                    ) { ?>

                        <tr>

                            <td>
                                <?php
                                echo $department["id"];
                                ?>
                            </td>

                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $department["name"]
                                );
                                ?>
                            </td>

                            <td>

                                <a
                                    href="departments.php?delete=<?php echo $department["id"]; ?>"
                                    class="department-delete"
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