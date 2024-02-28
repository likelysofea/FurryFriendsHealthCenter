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

    $query1 = "SELECT a.appointmentID, a.date, a.time, a.name, a.email, s.speciesName, a.reason, st.statusName 
    FROM appointment a 
    JOIN species s ON a.speciesID = s.speciesID 
    JOIN status st ON a.statusID = st.statusID WHERE a.email = ?";
    $stmt1 = $conn->prepare($query1);
    $stmt1->bind_param("s", $email);
    
    if ($stmt1->execute()) {
        // Store the result set
        $result1 = $stmt1->get_result();
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Appointment History</title>

        <script src="../assets/dist/js/bootstrap.bundle.min.js"></script>

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@docsearch/css@3">
        <link href="../assets/dist/css/bootstrap.min.css" rel="stylesheet"> 
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css">
        

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
        <div class="container mt-4">

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="../Customer Homepage/homepage.php">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Appointment History</li>
                </ol>
            </nav>

            <h1 class="display-5 fw-bold text-body-emphasis">Appointment History</h1>

            <div class="container mt-5" style="padding: 0;">
                <div class="col">
                    <table id="appointment-table" class="table custom-margin">
                        <thead>
                            <tr>
                            <th scope="col">Date</th>
                            <th scope="col">Time</th>
                            <th scope="col">Species</th>
                            <th scope="col">Reason</th>
                            <th scope="col">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                if ($result1->num_rows > 0) {
                                    while ($appointment = $result1->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php 
                                            $originalDate = $appointment['date'];
                                            $timestamp = strtotime($originalDate);
                                            $formattedDate = date("d-m-Y", $timestamp);
                                            echo $formattedDate;
                                        ?></td>
                                        <td><?php echo $appointment['time']; ?></td>
                                        <td><?php echo $appointment['speciesName']; ?></td>
                                        <td><?php echo $appointment['reason']; ?></td>
                                        <td>
                                            <?php
                                                // Determine the appropriate class based on status
                                                $statusClass = "";
                                                switch ($appointment['statusName']) {
                                                    case 'Pending':
                                                        $statusClass = "status-pending";
                                                        break;
                                                    case 'Confirmed':
                                                        $statusClass = "status-confirmed";
                                                        break;
                                                    case 'Cancelled':
                                                        $statusClass = "status-cancelled";
                                                        break;
                                                }
                                            ?>
                                            <span class="<?php echo $statusClass; ?>"><?php echo $appointment['statusName']; ?></span>
                                        </td>
                                    </tr>
                            <?php endwhile;
                                }else {
                                    // If there are no rows, display "No records found"
                                    echo "<tr><td colspan='5' style='text-align:center;'>No records found</td></tr>";
                                }
                                ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        

    </body>
</html>