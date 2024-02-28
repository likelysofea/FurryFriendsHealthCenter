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

    //Bind dropdown from database
    $query = "SELECT speciesID, speciesName FROM species";
    $result = $conn->query($query);

    //Save input data
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $date = isset($_POST['date']) ? $_POST['date'] : '';
        $time = isset($_POST['time']) ? $_POST['time'] : '';
        $name = isset($_POST['name']) ? $_POST['name'] : '';
        $phone = isset($_POST['phone']) ? $_POST['phone'] : '';
        $email = isset($_POST['email']) ? $_POST['email'] : '';
        $selectedSpeciesID = isset($_POST['species']) ? $_POST['species'] : '';
        $reason = isset($_POST['reason']) ? $_POST['reason'] : '';
        
        // Assuming a default status ID for new appointments. Change the value as needed.
        $defaultStatusID = 1;

        $sql = "INSERT INTO appointment (date, time, name, phoneNo, email, speciesID, reason, statusID) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssisi", $date, $time, $name, $phone, $email, $selectedSpeciesID, $reason, $defaultStatusID);
        
        if ($stmt->execute()) {
            echo "<script> 
                    alert('Your appointment is currently pending. Please check your email to view the approval status of your appointment.');
                    window.location.href = '../Customer Appointment Page/appointment_history.php';
                </script>";

        } else {
            echo "<script>
                    alert('Error: " . $stmt->error . "');
                </script>";
        }               
    
        $stmt->close();
    }

    //Close connection
    $conn->close();
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Book Appointment</title>

        <script src="../assets/dist/js/bootstrap.bundle.min.js"></script>
        <script src="../Customer Appointment Page/book_appointment.js"></script>

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
                    <li class="breadcrumb-item active" aria-current="page">Book Appointment</li>
                </ol>
            </nav>

            <h1 class="display-5 fw-bold text-body-emphasis">Book Appointment</h1>

            <form action= "" method="POST">
                <div class="form mt-5">
                    <!-- Date Section -->
                    <div class="row">
                        <label for="myDatepicker" class="col-sm-2 col-form-label">Date</label>
                        <div class="col-sm-10">
                            <input type="date" class="form-control" id="myDatepicker" name="date" required>
                            <div id="dateInvalidFeedback" style="color:red; display:none;">Weekends are not allowed!</div>
                        </div>
                    </div>
                    <!-- Time Section -->
                    <div class="row mt-4"> 
                        <label for="time" class="col-sm-2 col-form-label">Time</label>
                        <div class="col-sm-10">
                            <select class="form-select" id="time" name="time" required>
                                <option selected>Choose Time..</option>
                                <option value="9:00 AM">9:00 AM</option>
                                <option value="10:00 AM">10:00 AM</option>
                                <option value="11:00 AM">11:00 AM</option>
                                <option value="12:00 PM">12:00 PM</option>
                                <option value="2:00 PM">2:00 PM</option>
                                <option value="3:00 PM">3:00 PM</option>
                                <option value="4:00 PM">4:00 PM</option>
                                <option value="5:00 PM">5:00 PM</option>
                            </select>
                        </div>
                    </div>    
                    <!-- Name Section -->
                    <div class="row mt-4"> 
                        <label for="name" class="col-sm-2 col-form-label">Name</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                    </div> 
                    <!-- Phone no Section -->
                    <div class="row mt-4"> 
                        <label for="phone" class="col-sm-2 col-form-label">Phone No.</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="phone" name="phone" required>
                            <div id="phoneInvalidFeedback" style="color:red; display:none;">Invalid phone number format!</div>
                        </div>
                    </div> 
                    <!-- Email Section -->
                    <div class="row mt-4"> 
                        <label for="email" class="col-sm-2 col-form-label">Email</label>
                        <div class="col-sm-10">
                            <input type="email" class="form-control" id="email" placeholder="name@example.com" name="email" required>
                        </div>
                    </div> 
                    <!-- Species Section -->
                    <div class="row mt-4"> 
                        <label for="time" class="col-sm-2 col-form-label">Species</label>
                        <div class="col-sm-10">
                            <select class="form-select" id="species" name="species">
                            <?php
                                if($result->num_rows > 0){
                                    while($row = $result->fetch_assoc()){
                                        echo '<option value="'.$row['speciesID'].'">'.$row['speciesName'].'</option>';
                                    }
                                }else{
                                    echo '<option>No species found</option>';
                                }
                            ?>
                            </select>
                        </div>
                    </div> 
                    <!-- Reason Section-->
                    <div class="row mt-4"> 
                        <label for="reason" class="col-sm-2 col-form-label">Reason</label>
                        <div class="col-sm-10">
                            <textarea class="form-control" id="reason" name="reason" placeholder="Give reason(s) for vet visit" required></textarea>
                        </div>
                    </div> 
                    
                    <div class="mb-3 text-center" style="margin-top: 50px">
                        <button type="button" class="btn btn-secondary me-3" onclick="goBackBookAppointment()">Back</button>
                        <button type="submit" class="btn btn-primary">Confirm</button>
                    </div>
                </div>
            </form>
        </div>

        

    </body>
</html>