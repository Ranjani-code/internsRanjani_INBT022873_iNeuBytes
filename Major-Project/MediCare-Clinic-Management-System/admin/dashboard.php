<?php
session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../login.php");
    exit();
}

include "../config/database.php";

$admin_name = isset($_SESSION["name"]) && $_SESSION["name"] !== ""
    ? $_SESSION["name"]
    : "Administrator";

$patients = $conn->query(
    "SELECT COUNT(*) AS total FROM patients"
)->fetch_assoc()["total"];

$doctors = $conn->query(
    "SELECT COUNT(*) AS total FROM doctors"
)->fetch_assoc()["total"];

$appointments = $conn->query(
    "SELECT COUNT(*) AS total FROM appointments"
)->fetch_assoc()["total"];

$departments = $conn->query(
    "SELECT COUNT(*) AS total FROM departments"
)->fetch_assoc()["total"];
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Admin Dashboard - MediCare Clinic</title>

    <link rel="stylesheet" href="../css/style.css">

    <style>

        /* Admin Dashboard Layout */

        .admin-dashboard {
            padding: 55px 0 80px;
            background: #f5f8fa;
            min-height: calc(100vh - 160px);
        }

        .admin-dashboard-header {
            margin-bottom: 35px;
        }

        .admin-dashboard-header h1 {
            margin: 0 0 10px;
            color: #155b7a;
            font-size: 2.4rem;
        }

        .admin-dashboard-header p {
            margin: 0;
            color: #333333;
            font-size: 1rem;
        }


        /* Dashboard Cards */

        .admin-stat-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 24px;
            width: 100%;
        }

        .admin-stat-card {
            min-height: 145px;
            padding: 28px 26px;

            background: #ffffff;

            border: 1px solid #e2e8ec;
            border-radius: 12px;

            box-shadow: 0 5px 18px rgba(0, 0, 0, 0.07);

            display: flex;
            flex-direction: column;
            justify-content: center;

            box-sizing: border-box;
        }

        .admin-stat-card h3 {
            margin: 0 0 14px;

            color: #116f98;

            font-size: 1.05rem;
            font-weight: 700;
        }

        .admin-stat-card strong {
            display: block;

            color: #222222;

            font-size: 2rem;
            line-height: 1;
        }


        /* Dashboard Buttons */

        .admin-dashboard-actions {
            display: grid;

            grid-template-columns:
                repeat(5, minmax(150px, 1fr));

            gap: 16px;

            width: 100%;

            margin-top: 42px;

            padding-top: 0;

            clear: both;
        }

        .admin-dashboard-actions a {
            display: flex;

            align-items: center;
            justify-content: center;

            min-height: 48px;

            padding: 12px 16px;

            box-sizing: border-box;

            border-radius: 8px;

            background: #11759d;

            border: 1px solid #11759d;

            color: #ffffff;

            text-decoration: none;

            font-size: 0.88rem;
            font-weight: 600;

            text-align: center;

            white-space: nowrap;

            transition:
                background-color 0.2s ease,
                transform 0.2s ease;
        }

        .admin-dashboard-actions a:hover {
            background: #0d607f;
            border-color: #0d607f;
            transform: translateY(-1px);
        }


        /* Footer */

        .admin-dashboard + footer {
            margin-top: 0;
        }


        /* Tablet */

        @media (max-width: 1100px) {

            .admin-stat-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .admin-dashboard-actions {
                grid-template-columns: repeat(2, 1fr);
            }

        }


        /* Mobile */

        @media (max-width: 600px) {

            .admin-dashboard {
                padding: 40px 0 60px;
            }

            .admin-dashboard-header h1 {
                font-size: 2rem;
            }

            .admin-stat-grid {
                grid-template-columns: 1fr;
                gap: 16px;
            }

            .admin-dashboard-actions {
                grid-template-columns: 1fr;
                gap: 12px;
                margin-top: 30px;
            }

            .admin-dashboard-actions a {
                width: 100%;
            }

        }
        /* FINAL DASHBOARD ALIGNMENT */

.admin-dashboard-header {
    text-align: center;
}

.admin-dashboard-header p {
    text-align: center;
}

.admin-stat-grid {
    width: 100%;
}

.admin-dashboard-actions {
    width: 100%;
    justify-content: center;
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


<section class="admin-dashboard">

    <div class="container">


        <div class="admin-dashboard-header">

            <h1>
                Admin Dashboard
            </h1>

            <p>
                Welcome,
                <strong>
                    <?php echo htmlspecialchars($admin_name); ?>
                </strong>
            </p>

        </div>


        <!-- Statistics -->

        <div class="admin-stat-grid">


            <div class="admin-stat-card">

                <h3>
                    Total Patients
                </h3>

                <strong>
                    <?php echo $patients; ?>
                </strong>

            </div>


            <div class="admin-stat-card">

                <h3>
                    Total Doctors
                </h3>

                <strong>
                    <?php echo $doctors; ?>
                </strong>

            </div>


            <div class="admin-stat-card">

                <h3>
                    Total Appointments
                </h3>

                <strong>
                    <?php echo $appointments; ?>
                </strong>

            </div>


            <div class="admin-stat-card">

                <h3>
                    Total Departments
                </h3>

                <strong>
                    <?php echo $departments; ?>
                </strong>

            </div>


        </div>


        <!-- Management Buttons -->

        <div class="admin-dashboard-actions">

            <a href="doctors.php">
                Manage Doctors
            </a>

            <a href="patients.php">
                Manage Patients
            </a>

            <a href="departments.php">
                Manage Departments
            </a>

            <a href="appointments.php">
                Manage Appointments
            </a>

            <a href="reports.php">
                View Reports
            </a>

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