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

    //Bind dropdown from database
    $query = "SELECT statusID, statusName FROM status";
    $result = $conn->query($query);

    //Bind table from database
    $query1 = "SELECT a.appointmentID, a.date, a.time, a.name, s.speciesName, a.reason, st.statusName 
    FROM appointment a 
    JOIN species s ON a.speciesID = s.speciesID 
    JOIN status st ON a.statusID = st.statusID
    ORDER BY a.date DESC, a.time DESC";
    
    $result1 = $conn->query($query1);

    //Close connection
    $conn->close();

?>
        <div class="flex-grow-1 p-3" style="overflow-y: auto; margin-top: -20px;">
            <div class="container-fluid">
                <div class="row">
                    <!-- Ensure the main content area takes the remaining space after the 280px sidebar -->
                    <div class="col-md-9 col-lg-10 px-md-4">

                        <h1 class="display-5 fw-bold text-body-emphasis">Appointments</h1>

                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="../Admin Homepage/homepage.php">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Appointments</li>
                            </ol>
                        </nav>  

                        <div class="search-filter-section d-flex align-items-center justify-content-between mt-5" style="max-width: 1200px; margin: 30 auto; ">
                            <div class="d-flex align-items-center">
                                <input class="form-control form-control-lg search-input  me-6" type="text" placeholder="Search...">
                                <div class="filter d-flex align-items-center">
                                    <label class="col-sm-2 col-form-label col-form-label-lg me-3">Filter:</label>
                                    <select class="form-select form-select-lg" id="filter">
                                        <option selected>None</option>
                                            <?php
                                                if($result->num_rows > 0){
                                                    while($row = $result->fetch_assoc()){
                                                        echo '<option value="'.$row['statusID'].'">'.$row['statusName'].'</option>';
                                                    }
                                                }else{
                                                    echo '<option>No status found</option>';
                                                }
                                            ?>
                                    </select>
                                </div>
                                <a href="../Admin Appointment Page/create_appointment.php">
                                    <button type="button" class="btn btn-primary btn-lg"><i class="bi bi-plus-lg"></i></button>
                                </a>
                            </div>
                        </div>

                        <div class="row mt-5">
                                <div class="col">
                                    <table id="appointment-table" class="table custom-margin">
                                        <thead>
                                            <tr>
                                            <th scope="col">Name</th>
                                            <th scope="col">Date</th>
                                            <th scope="col">Time</th>
                                            <th scope="col">Species</th>
                                            <th scope="col">Reason</th>
                                            <th scope="col">Status</th>
                                            <th scope="col">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php while ($appointment = mysqli_fetch_assoc($result1)): ?>
                                            <tr>
                                                <td><?php echo $appointment['name']; ?></td>
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
                                                <td><a href="../Admin Appointment Page/view_appointment.php?id=' . $appointment['appointmentID'] . '" class="btn btn-primary"><i class="bi bi-eye"></i> View</a></td>
                                            </tr>
                                            <?php endwhile; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                    </div>
                </div>
            </div>
        </div>
        

</main>
    </body>    
</html>