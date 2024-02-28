<?php
    session_start();

    //Include database
    include '../db_conn.php';

    // Check if the user is not logged in
    if (!isset($_SESSION["userID"])) {
        header("Location: ../Admin Login Page/");
        exit();
    }

    // Retrieve the username from the session
    $username = isset($_SESSION["username"]) ? $_SESSION["username"] : "";

    // The user is logged in, continue displaying the homepage
    include '../sidebar.php';

    if (isset($_GET['id']) && !empty($_GET['id'])) {
        $appointmentID = $_GET['id'];

        //Sanitize input to prevent from sql injection
        $appointmentID = mysqli_real_escape_string($conn, $appointmentID);

        $query = "SELECT a.date, a.time, a.name, a.phoneNo, a.email, s.speciesName, a.reason, st.statusName 
        FROM Appointment a 
        JOIN species s ON a.speciesID = s.speciesID 
        JOIN status st ON a.statusID = st.statusID
        WHERE a.appointmentID = $appointmentID";

        $result = mysqli_query($conn, $query);
        
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            //Change date format
            $dateOriginal = $row['date'];
            $dateObject = DateTime::createFromFormat('Y-m-d', $dateOriginal);
            
            if ($dateObject !== false) {  // Check if DateTime creation was successful
                $dateFormatted = $dateObject->format('d-m-Y');
            } else {
                $dateFormatted = $dateOriginal;  // Default to original if conversion failed
            }

            // Assume you've already retrieved the $row array from the database
            $status = $row['statusName'];

            echo "
                <script>
                    var appointmentID = '{$appointmentID}';
                    var statusName = '{$row['statusName']}';
                    var dateFormatted = '{$dateFormatted}';
                    var time = '{$row['time']}';
                    var name = '{$row['name']}';
                    var phoneNo = '{$row['phoneNo']}';
                    var email = '{$row['email']}';
                    var species = '{$row['speciesName']}';
                    var reason = '{$row['reason']}';
                </script>
                ";
            

            echo "<script src='../Admin Appointment Page/view_appointment.js'></script>";

        }
    }

?>

        <div class="flex-grow-1 p-3" style="overflow-y: auto; margin-top: -20px; margin-left: auto;">
            <div class="container-fluid">
                <div class="row">
                    <!-- Ensure the main content area takes the remaining space after the 280px sidebar -->
                    <div class="col-md-9 col-lg-10 px-md-4">
                        
                        <h1 class="display-5 fw-bold text-body-emphasis">View Appointment Details</h1>
                        
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="../Admin Homepage/homepage.php">Home</a></li>
                                <li class="breadcrumb-item"><a href="../Admin Appointment Page/appointment.php">Appointments</a></li>
                                <li class="breadcrumb-item active" aria-current="page">View Appointment Details</li>
                            </ol>
                        </nav>

                        <form>
                            <div class="form">
                                <div class="col-sm-10">
                                        <input type="hidden" readonly class="form-control-plaintext" id="appointmentID">
                                    </div>
                                <!-- Status section-->
                                <div class="row">
                                    <label for="status" class="col-sm-2 col-form-label">Status</label>
                                    <div class="col-sm-10">
                                        <input type="text" readonly class="form-control-plaintext" id="status">
                                    </div>
                                </div>
                                <!-- Date Section -->
                                <div class="row mt-4">
                                    <label for="myDatepicker" class="col-sm-2 col-form-label">Date</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="date" disabled readonly>
                                    </div>
                                </div>
                                <!-- Time Section -->
                                <div class="row mt-4"> 
                                    <label for="time" class="col-sm-2 col-form-label">Time</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="time" disabled readonly>
                                    </div>
                                </div>     
                                <!-- Name Section -->
                                <div class="row mt-4"> 
                                    <label for="name" class="col-sm-2 col-form-label">Name</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="name" disabled readonly>
                                    </div>
                                </div> 
                                <!-- Phone no Section -->
                                <div class="row mt-4"> 
                                    <label for="phone" class="col-sm-2 col-form-label">Phone No.</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="phoneNo" disabled readonly>
                                    </div>
                                </div> 
                                <!-- Email Section -->
                                <div class="row mt-4"> 
                                    <label for="email" class="col-sm-2 col-form-label">Email</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="email" disabled readonly>
                                    </div>
                                </div> 
                                <!-- Species Section -->
                                <div class="row mt-4"> 
                                    <label for="time" class="col-sm-2 col-form-label">Species</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="species" disabled readonly>
                                    </div>
                                </div> 
                                <!-- Reason Section-->
                                <div class="row mt-4"> 
                                    <label for="reason" class="col-sm-2 col-form-label">Reason</label>
                                    <div class="col-sm-10">
                                        <textarea class="form-control" id="reason" disabled readonly></textarea>
                                    </div>
                                </div> 

                                <div class="row mt-5">
                                    <div class="mb-3 text-center" >
                                        <button type="button" class="btn btn-secondary btn-reduce-spacing me-3" onclick="goBackViewAppointment()">Back</button>
                                        <a id="confirm" class="btn btn-success me-3">Confirm</a>
                                        <a id="cancel" class="btn btn-danger me-3">Cancel</a>
                                    </div>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>

</main>
    </body>    
</html>