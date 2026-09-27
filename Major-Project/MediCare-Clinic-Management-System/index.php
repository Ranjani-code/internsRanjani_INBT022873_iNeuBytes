<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MediCare Clinic</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

<header>
    <div class="logo">MediCare Clinic</div>

    <nav>
        <a href="index.php">Home</a>
        <a href="login.php">Login</a>
        <a href="register.php">Register</a>
    </nav>
</header>

<section class="hero">
    <div>
        <h1>Quality Healthcare for You and Your Family</h1>
        <p>
            Compassionate care, experienced doctors and convenient
            appointment services for your healthcare needs.
        </p>

        <a href="login.php" class="btn">Book an Appointment</a>
    </div>
</section>

<section class="section">
    <h2>Our Services</h2>

    <div class="cards">
        <div class="card">
            <h3>General Medicine</h3>
            <p>Medical consultation and treatment for common health concerns.</p>
        </div>

        <div class="card">
            <h3>Cardiology</h3>
            <p>Specialized care for heart and cardiovascular conditions.</p>
        </div>

        <div class="card">
            <h3>Pediatrics</h3>
            <p>Healthcare services focused on children and their wellbeing.</p>
        </div>

        <div class="card">
            <h3>Dermatology</h3>
            <p>Professional care for skin, hair and related conditions.</p>
        </div>
    </div>
</section>

<section class="section about">
    <h2>Why Choose MediCare?</h2>

    <div class="cards">
        <div class="card">
            <h3>Experienced Doctors</h3>
            <p>Our doctors provide attentive and personalized medical care.</p>
        </div>

        <div class="card">
            <h3>Easy Appointments</h3>
            <p>Book and manage appointments conveniently through your account.</p>
        </div>

        <div class="card">
            <h3>Patient-Focused Care</h3>
            <p>We focus on providing a comfortable and supportive experience.</p>
        </div>
    </div>
</section>

<section class="cta">
    <h2>Take Care of Your Health</h2>
    <p>Book an appointment with our medical professionals.</p>
    <a href="login.php" class="btn">Get Started</a>
</section>

<footer>
    <p>&copy; 2026 MediCare Clinic. All Rights Reserved.</p>
</footer>
<script src="js/script.js"></script>
</body>
</html>