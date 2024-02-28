<?php
    session_start();

    //Include database
    include '../db_conn.php';

    require '../config.php';

    // Import PHPMailer classes
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    // Check if the user is not logged in
    if (!isset($_SESSION["userID"])) {
        header("Location: ../Admin Login Page/");
        exit();
    }

    // Retrieve the username from the session
    $username = isset($_SESSION["username"]) ? $_SESSION["username"] : "";

    // The user is logged in, continue displaying the homepage
    include '../sidebar.php';

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
        
        // Assuming a default status ID for new appointments. 
        $defaultStatusID = 2;

        $sql = "INSERT INTO appointment (date, time, name, phoneNo, email, speciesID, reason, statusID) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssisi", $date, $time, $name, $phone, $email, $selectedSpeciesID, $reason, $defaultStatusID);
        
        if ($stmt->execute()) {

            // Try to send a confirmation email using PHPMailer
            try{
                require '../PHPMailer/src/Exception.php';
                require '../PHPMailer/src/PHPMailer.php';
                require '../PHPMailer/src/SMTP.php';

                $mail = new PHPMailer(true);
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = $email_username;
                $mail->Password = $email_password;
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;

                //Recipients
                $mail->setFrom('cupcakealiesya@gmail.com', 'Vet Clinic');
                $mail->addAddress($email, $name);

                // Content
                $mail->isHTML(true); // Set email format to HTML
                $mail->Subject = 'Appointment Confirmation';
                $mail->Body    = 'Your appointment has been successfully booked for ' . $date . ' at ' . $time . '.';
                
                $mail->send();
            
            } catch (Exception $e){
                echo "<script> alert('Message could not be sent. Mailer Error: {$mail->ErrorInfo}'); </script>";
            }

            echo "<script> 
                    alert('Appointment saved and email has been sent successfully!');
                    window.location.href = 'appointment.php';
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
        <div class="flex-grow-1 p-3" style="overflow-y: auto; margin-top: -20px; margin-left: auto;">
            <div class="container-fluid">
                <div class="row">
                    <!-- Ensure the main content area takes the remaining space after the 280px sidebar -->
                    <div class="col-md-9 col-lg-10 px-md-4">
                        
                        <h1 class="display-5 fw-bold text-body-emphasis">Create New Appointment</h1>

                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="../Admin Homepage/homepage.php">Home</a></li>
                                <li class="breadcrumb-item"><a href="../Admin Appointment Page/appointment.php">Appointments</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Create New Appointment</li>
                            </ol>
                        </nav>

                        <form action= "" method="POST">
                            <div class="form">
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
                                            <option selected>Choose Time</option>
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
                                    <button type="button" class="btn btn-secondary me-3" onclick="goBackCreateAppointment()">Back</button>
                                    <button type="submit" class="btn btn-primary">Confirm</button>
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