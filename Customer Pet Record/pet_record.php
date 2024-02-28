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

    //Bind table from database
    $query2 = "SELECT p.recordID, p.petName, s.speciesName, c.email FROM pet_record p JOIN species s ON p.speciesID = s.speciesID JOIN customer c ON p.icNo = c.icNo WHERE c.email = ?";
    $stmt2 = $conn->prepare($query2);
    $stmt2->bind_param("s", $email);

    if ($stmt2->execute()) {
        // Store the result set
        $result2 = $stmt2->get_result();
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
                    <li class="breadcrumb-item active" aria-current="page">View Pet Record</li>
                </ol>
            </nav>

            <h1 class="display-5 fw-bold text-body-emphasis">Pet Record</h1>

            <div class="container mt-5" style="padding: 0;">
                <div class="col">
                    <table id="pet-record-table" class="table custom-margin">
                        <thead>
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Pet Name</th>
                                <th scope="col">Species</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($record = mysqli_fetch_assoc($result2)): ?>
                            <tr> 
                                <td><?php echo $record['recordID']; ?></td>
                                <td><?php echo $record['petName']; ?></td>
                                <td><?php echo $record['speciesName']; ?></td>
                                <td><a href="../Customer Pet Record/view_record.php?id=<?php echo $record['recordID']; ?>" class="btn btn-outline-primary">View</a></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        

    </body>
</html>