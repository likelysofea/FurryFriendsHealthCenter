<?php
    session_start();

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

    //Count total number of appointments
    $appointment_query = "SELECT COUNT(*) AS total_appointments FROM appointment";
    $appointment_result = mysqli_query($conn, $appointment_query);

    if($appointment_result) {
        $row = mysqli_fetch_assoc($appointment_result);
        $totalAppointments = $row['total_appointments'];
    } else {
        $totalAppointments = "Error: " . mysqli_error($conn);
    }

    //Cound total number of pet record
    $record_query = "SELECT COUNT(*) AS total_records FROM pet_record";
    $record_result = mysqli_query($conn, $record_query);

    $totalRecords = 0;
    if($record_result) {
        $row = mysqli_fetch_assoc($record_result);
        $totalRecords = $row['total_records'];
    } else {
        $totalRecords = "Error: " . mysqli_error($conn);
    }

    //Bind today's confirmed appointments
    $currentDate = date('Y-m-d');

    $today_query = "SELECT name, time FROM appointment WHERE date = ? AND statusID = 2";
    $stmt = mysqli_prepare($conn, $today_query);
    mysqli_stmt_bind_param($stmt, "s", $currentDate);
    mysqli_stmt_execute($stmt);
    $today_result = mysqli_stmt_get_result($stmt);

    $todaysAppointments = [];
    while ($row = mysqli_fetch_assoc($today_result)) {
        $todaysAppointments[] = $row;
    }

    //Bind items where the quantity is less than or equal to the reorder threshold
    $inventory_query = "SELECT itemName, quantity FROM inventory WHERE quantity <= reorder_threshold";
    $inventory_result = mysqli_query($conn, $inventory_query);

    // Fetch the results
    $lowStockItems = [];
    while ($row = mysqli_fetch_assoc($inventory_result)) {
        $lowStockItems[] = $row;
    }


    $conn->close();
?>

        <div class="flex-grow-1 p-3" style="overflow-y: auto; margin-top: -20px;">
            <div class="container-fluid">
                <div class="row">
                    <!-- Ensure the main content area takes the remaining space after the 280px sidebar -->
                    <div class="col-md-9 col-lg-10 px-md-4">
                        <!-- For the title, ensure the text alignment is set to left -->
                        <h1 class="display-5 fw-bold text-body-emphasis text-start">Home</h1>
                        
                        <div class="d-flex mt-5">
                            <div class="row">
                                <!-- First card -->
                                <div class="col">
                                <div class="card text-center" style="width: 15rem; margin-left: 30px;">
                                    <div class="card-body">
                                    <i class="bi bi-calendar" style="font-size: 1.5rem;"></i>
                                    </div>
                                    <h5 class="card-title">Appointments</h5>
                                    <p class="card-number" style="font-size: 2.5rem; font-weight: bold;"><?php echo $totalAppointments; ?></p>
                                </div>
                                </div>
                                <!-- Second card, similar to the first one -->
                                <div class="col">
                                <div class="card text-center" style="width: 15rem;">
                                    <div class="card-body">
                                        <i class="bi bi-file-earmark-medical" style="font-size: 1.5rem;"></i> 
                                    </div>
                                    <h5 class="card-title">Total Patients</h5>
                                    <p class="card-number" style="font-size: 2.5rem; font-weight: bold;"><?php echo $totalRecords; ?></p>
                                </div>
                                </div>
                            </div>
                        </div>


                        <div class="d-flex">
                            <div class="row">
                                <div class="col">
                                    <div class="card mt-5" style="width: 25rem; margin-left: 30px;">
                                        <div class="card-header">
                                            <b>Today's Appointments</b>
                                        </div>
                                        <ul class="list-group list-group-flush">
                                            <?php foreach ($todaysAppointments as $appointment): ?>
                                            <li class="list-group-item"><?php echo htmlspecialchars($appointment['name']) . ' - ' . htmlspecialchars($appointment['time']); ?></li>
                                            <?php endforeach; ?>
                                            <?php if (count($todaysAppointments) === 0): ?>
                                            <li class="list-group-item">No confirmed appointments for today.</li>
                                            <?php endif; ?>
                                        </ul>
                                    </div>
                                </div>

                                <div class="col">
                                    <div class="card mt-5" style="width: 25rem;">
                                        <div class="card-header">
                                        <b>Inventory Low Stock Alert</b>
                                        </div>
                                        <ul class="list-group list-group-flush">
                                        <?php foreach ($lowStockItems as $item): ?>
                                            <li class="list-group-item"><?php echo htmlspecialchars($item['itemName']) . ' - ' . htmlspecialchars($item['quantity']); ?></li>
                                        <?php endforeach; ?>
                                        <?php if (count($lowStockItems) === 0): ?>
                                            <li class="list-group-item">No low stock items.</li>
                                        <?php endif; ?>
                                        </ul>
                                    </div>
                                </div>
                                

                            </div>
                        </div>
                        
                        
                    </div>
                </div>
            </div>
        </div>
        

</main>
    </body>    
</html>