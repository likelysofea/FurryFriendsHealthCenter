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
    $query = "SELECT speciesID, speciesName FROM species";
    $result = $conn->query($query);

    //Bind table from database
    $query2 = "SELECT p.recordID, p.petName, s.speciesName, c.name FROM pet_record p JOIN species s ON p.speciesID = s.speciesID JOIN customer c ON p.icNo = c.icNo ORDER BY p.recordID DESC";
    $result2 = $conn->query($query2);

    //Close connection
    $conn->close();
?>
        <div class="flex-grow-1 p-3" style="overflow-y: auto; margin-top: -20px;">
                <div class="container-fluid">
                    <div class="row">
                        <!-- Ensure the main content area takes the remaining space after the 280px sidebar -->
                        <div class="col-md-9 col-lg-10 px-md-4">

                            <h1 class="display-5 fw-bold text-body-emphasis">Pet Records</h1>

                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="../Admin Homepage/homepage.php">Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Pet Records</li>
                                </ol>
                            </nav> 

                            <div class="search-filter-section d-flex align-items-center justify-content-between mt-5" style="max-width: 1200px; margin: 30 auto; ">
                                <div class="d-flex align-items-center">
                                    <input class="form-control form-control-lg pet_record_search me-6" type="text" placeholder="Search...">
                                    <div class="filter d-flex align-items-center">
                                        <label class="col-sm-2 col-form-label col-form-label-lg me-3">Filter:</label>
                                        <select class="form-select form-select-lg" id="pet_record_filter">
                                            <option selected>None</option>
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
                                    <a href="../Admin Pet Record Page/create_pet_record.php">
                                        <button type="button" class="btn btn-primary btn-lg"><i class="bi bi-plus-lg"></i></button>
                                    </a>
                                </div>
                            </div> 
                            
                            <div class="row mt-5">
                                <div class="col">
                                    <table id="pet-record-table" class="table custom-margin">
                                        <thead>
                                            <tr>
                                                <th scope="col">ID</th>
                                                <th scope="col">Pet Name</th>
                                                <th scope="col">Species</th>
                                                <th scope="col">Owner Name</th>
                                                <th scope="col">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php while ($record = mysqli_fetch_assoc($result2)): ?>
                                            <tr> 
                                                <td><?php echo $record['recordID']; ?></td>
                                                <td><?php echo $record['petName']; ?></td>
                                                <td><?php echo $record['speciesName']; ?></td>
                                                <td><?php echo $record['name']; ?></td>
                                                <td><a href="../Admin Pet Record Page/view_record.php?id=' . $record['recordID'] . '" class="btn btn-primary"><i class='bi bi-eye'></i>View</a></td>
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
        </div>

</main>
    </body>    
</html>