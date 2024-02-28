<?php
    session_start();
    include '../db_conn.php';

    // Check if the user is not logged in
    if (!isset($_SESSION["loginID"])) {
        header("Location: ../Customer Login Page/customer_login.php");
        exit();
    }

    // Retrieve the email from the session
    $email = isset($_SESSION["email"]) ? $_SESSION["email"] : "";

    if (!empty($email)) {
        $stmt = $conn->prepare("SELECT nickname FROM login WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->bind_result($nickname);

        if ($stmt->fetch()) {
            // $nickname now contains the nickname associated with the email
            $stmt->close();
        } else {
            // Handle the case where no nickname was found for the email
            $nickname = "User"; // You can set a default value if needed
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Homepage</title>

        <script src="../assets/dist/js/bootstrap.bundle.min.js"></script>

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@docsearch/css@3">
        <link href="../assets/dist/css/bootstrap.min.css" rel="stylesheet"> 
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css">

        <style>
        .invisible-link {
            text-decoration: none; /* No underline */
            color: inherit; /* Text color same as parent */
            display: block; /* Make the link fill the card body for clickable area */
            padding: 0.5rem 1rem; /* Standard padding to match card body */
        }
        .invisible-link:hover, .invisible-link:focus {
            text-decoration: none;
            color: inherit;
            background-color: rgba(0,0,0,.03); /* Optional: slight change on hover/focus */
        }
        .card-body {
            padding: 0;
        }
        </style>

    </head>
    <body>
        <!-- Navigation Bar -->
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container">
                <!-- Company Logo and Name -->
                <a class="navbar-brand" href="#"><img src="../Profile Picture/Garfield-and-Odie.png" alt="Company Logo" height="60"></a>
                <span class="navbar-text mx-3">Furry Friends Health Center</span>
                
                <!-- Spacer to push the following items to the right -->
                <div class="flex-grow-1"></div>
                
                <!-- User Nickname and Logout Button -->
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <span class="nav-link">Welcome, <?php echo htmlspecialchars($nickname); ?></span>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-outline-primary" href="../Customer Homepage/logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </nav>

        <!--Main Content-->
        <div class="container mt-5">
        <div class="row">
            <!-- Appointment Card -->
            <div class="col-md-6 mb-3">
            <div class="card">
                <div class="card-header"><i class="bi bi-calendar-plus"></i>
                &nbsp Appointment
                </div>
                <div class="card-body">
                <a href="../Customer Appointment Page/book_appointment.php" class="invisible-link">Book Appointment</a>
                </div>
                <div class="card-body">
                <a href="../Customer Appointment Page/appointment_history.php" class="invisible-link">Appointment History</a>
                </div>
            </div>
            </div>
            
            <!-- Pet Record Card -->
            <div class="col-md-6 mb-3">
            <div class="card">
                <div class="card-header"><i class="bi bi-clipboard-heart"></i>
                &nbsp Pet Record
                </div>
                <div class="card-body">
                <a href="../Customer Pet Record/pet_record.php" class="invisible-link">View Pet Record</a>
                </div>
            </div>
            </div>
        </div>
        </div>

    </body>
</html>