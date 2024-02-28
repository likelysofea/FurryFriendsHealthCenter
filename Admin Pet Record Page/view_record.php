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

    // Get ID to retrieve information for
    if (isset($_GET['id']) && !empty($_GET['id'])){
        $recordID = $_GET['id'];

        //Sanitize input to prevent from sql injection
        $recordID = mysqli_real_escape_string($conn, $recordID);

        $record_query = "SELECT p.petName, p.age, p.gender, p.weight, p.petImage, p.icNo, c.name, c.email, c.phoneNo, c.address, s.speciesName FROM pet_record p JOIN customer c ON p.icNo = c.icNo JOIN species s ON p.speciesID = s.speciesID WHERE p.recordID = $recordID";

        $record_result = mysqli_query($conn, $record_query);

        if ($record_result->num_rows > 0){
            $row = $record_result->fetch_assoc();

            echo "
                <script>
                    var petImage = '{$row['petImage']}';
                    var recordID = '{$recordID}';
                    var petName = '{$row['petName']}';
                    var species = '{$row['speciesName']}';
                    var gender = '{$row['gender']}';
                    var age = '{$row['age']}';
                    var weight = '{$row['weight']}';
                    var icNo = '{$row['icNo']}';
                    var ownerName = '{$row['name']}';
                    var phoneNo = '{$row['phoneNo']}';
                    var email = '{$row['email']}';
                    var address = '{$row['address']}';
                </script>
                ";
            

            echo "<script src='../Admin Pet Record Page/view_record.js'></script>";
        }
    }

?>

        <div class="flex-grow-1 p-3" style="overflow-y: auto; margin-top: -20px;">
            <div class="container-fluid">
                <div class="row">
                    <!-- Ensure the main content area takes the remaining space after the 280px sidebar -->
                    <div class="col-md-9 col-lg-10 px-md-4">

                        <h1 class="display-5 fw-bold text-body-emphasis">View Pet Record</h1>

                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="../Admin Homepage/homepage.php">Home</a></li>
                                <li class="breadcrumb-item"><a href="../Admin Pet Record Page/pet_record.php">Pet Record</a></li>
                                <li class="breadcrumb-item active" aria-current="page">View Pet Record</li>
                            </ol>
                        </nav>  

                        <div class="card mb-3 mt-5" style="max-width: 1200px; margin-left: 30px;">
                           
                            <div class="card-body">
                                
                                <!--Pet Information-->
                                <h3 class="card-title d-flex justify-content-between align-items-center">Pet Information

                                    <div class="button-container">
                                        <a id="cancelButton" class="btn btn-secondary" style="display: none; margin-right: 0px;">Cancel</a>
                                        <a id="editButton" class="btn btn-primary">Edit</a>
                                    </div>

                                </h3>
                                
                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <div class="mb-3 mt-3">
                                            <label for="petPicture" class="form-label">Pet Picture:</label>
                                            <div>
                                                <img id="petPicture" src="" alt="Pet Picture" class="img-fluid" style="object-fit: cover; height: 200px; " />
                                            </div>
                                        </div>
                                    </div>
                                
                                    <div class="col-sm mt-3">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="recordID" class="form-label">ID:</label>
                                                    <input type="text" class="form-control" id="recordID" name="recordID" disabled readonly>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="petName_record" class="form-label">Pet Name:</label>
                                                    <input type="text" class="form-control" id="petName_record" name="petName_record" disabled>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="species_record" class="form-label">Species:</label>
                                                    <input type="text" class="form-control" id="species_record" name="species_record" disabled readonly>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="gender_record" class="form-label">Gender:</label>
                                                    <select class="form-control" id="gender_record" name="gender_record" disabled>
                                                        <option value="Male">Male</option>
                                                        <option value="Female">Female</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="age_record" class="form-label">Age:</label>
                                                    <input type="text" class="form-control" id="age_record" name="age_record" disabled>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="weight_record" class="form-label">Weight (kg):</label>
                                                    <input type="text" class="form-control" id="weight_record" name="weight_record" disabled>
                                                </div>
                                            </div>

                                        </div>
                                                                    
                                    </div>
                                </div>

                                <!--Owner Information-->
                                <h3 class="card-title d-flex justify-content-between align-items-center mt-4">Owner Information</h3>
                                
                                <div class="row mb-3">
                                    
                                    <div class="row mt-3">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="icNo_record" class="form-label">IC No:</label>
                                                <input type="text" class="form-control" id="icNo_record" name="icNo_record" disabled readonly>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="ownerName_record" class="form-label">Owner Name:</label>
                                                <input type="text" class="form-control" id="ownerName_record" name="ownerName_record" disabled>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="phoneNo_record" class="form-label">Phone No:</label>
                                                <input type="text" class="form-control" id="phoneNo_record" name="phoneNo_record" disabled>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="email_record" class="form-label">Email:</label>
                                                <input type="text" class="form-control" id="email_record" name="email_record" disabled>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label for="address_record" class="form-label">Address:</label>
                                                <input type="text" class="form-control" id="address_record" name="address_record" disabled>
                                            </div>
                                        </div> 

                                    </div>

                                    
                                        
                                </div>

                            </div>


                            
                        </div>

                        <div class="card mb-3 mt-4" style="max-width: 1200px; margin-left: 30px;">
                            <div class="card-body">
                                <!--Pet Vaccination Details-->
                                <h3 class="card-title d-flex justify-content-between align-items-center">Pet Vaccination Details
                                    <a id="addBtn" class="btn btn-primary">Add</a>
                                </h3>

                                <table class="table mt-4">
                                    <thead>
                                        <tr>
                                            <th scope="col">No.</th>
                                            <th scope="col">Vaccine Type</th>
                                            <th scope="col">Vaccination Date</th>
                                            <th scope="col">Next Vaccination Date</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="vaccineRecordsBody">
                                    <!-- Dynamic rows will be added here -->
                                    </tbody>
                                </table>
                                
                                <!--Vaccine Modal-->
                                <div class="modal fade" id="vaccineModal" tabindex="-1" aria-labelledby="vaccineModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="vaccineModalLabel">Vaccine Detail</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <!-- Vaccine Form -->
                                                <form id="vaccineForm" style="margin: 0;">
                                                    <div class="mb-3">
                                                        <label for="vaccineType" class="form-label">Vaccine Type</label>
                                                        <select class="form-control" id="vaccineType">
                                                            <option value="Dog Distemper">Dog Distemper</option>
                                                            <option value="Feline Distemper">Feline Distemper</option>
                                                            <option value="Parvovirus">Parvovirus</option>
                                                            <option value="Rabies">Rabies</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="vaccinationDate" class="form-label">Vaccination Date</label>
                                                        <input type="date" class="form-control" id="vaccinationDate" readonly>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="nextVaccinationDate" class="form-label">Next Vaccination Date</label>
                                                        <input type="date" class="form-control" id="nextVaccinationDate" required>
                                                        <div id="vaccinationDateFeedback" style="color:red; display:none;">Weekends are not allowed!</div>
                                                    </div>
                                                    <button type="submit" class="btn btn-primary">Submit</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Edit Vaccination Date Modal -->
                                <div class="modal" tabindex="-1" role="dialog" id="editVaccinationModal">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit Next Vaccination Date</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form id="editVaccinationForm" style="margin: 0;">
                                                    <div class="form-group">
                                                        <label for="nextVaccinationDateModal">Next Vaccination Date</label>
                                                        <input type="date" class="form-control" id="nextVaccinationDateModal" style="margin-top: 10px;" required>
                                                        <div id="nextVaccinationDateFeedback" style="color:red; display:none;">Weekends are not allowed!</div>
                                                    </div>
                                                    <input type="hidden" id="recordIdModal" value="">
                                                    <button type="submit" class="btn btn-primary" style="margin-top: 20px;">Save changes</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <!--Invoice-->
                        <div class="card mb-3 mt-4" style="max-width: 1200px; margin-left: 30px;">
                            <div class="card-body">
                                <!--Invoice pdf-->
                                <h3 class="card-title d-flex justify-content-between align-items-center">Invoice
                                    <a id="InvoiceBtn" class="btn btn-primary">Create Invoice</a>
                                    <input type="hidden" id="icNo_invoice" value="<?php echo htmlspecialchars($row['icNo']); ?>">
                                </h3>
                                <table class="table mt-4">
                                    <thead>
                                        <tr>
                                            <th scope="col">Receipt No.</th>
                                            <th scope="col">Date</th>
                                            <th scope="col">Total Amount (RM)</th>
                                            <th scope="col">Payment Method</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="invoiceRecordsBody">
                                    <!-- Dynamic rows will be added here -->
                                    </tbody>
                                </table>
                                
                            </div>
                        </div>

                        <div class="mb-3 text-center" style="margin-top: 50px">
                            <a id="backBtn" class="btn btn-secondary me-3" onclick="goBackPetRecord()">Back</a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        

</main>
    </body>    
</html>