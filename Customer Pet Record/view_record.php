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
            

            echo "<script src='../Customer Pet Record/view_record.js'></script>";
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>View Pet Record</title>

        <script src="../assets/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="../Customer Pet Record/view_record.js"></script>

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

            <div class="container mt-4" style="padding: 0;">
                <div class="card-body">    
                    <!--Pet Information-->
                    <h3 class="card-title d-flex justify-content-between align-items-center">Pet Information

                        <div class="button-container">
                            <a id="cancelButton" class="btn btn-secondary" style="display: none; margin-right: 0px;">Cancel</a>
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

                <div class="card-body mt-5">
                    <!--Pet Vaccination Details-->
                    <h3 class="card-title d-flex justify-content-between align-items-center">Pet Vaccination Details</h3>
                    <table class="table mt-4">
                        <thead>
                            <tr>
                                <th scope="col">No.</th>
                                <th scope="col">Vaccine Type</th>
                                <th scope="col">Vaccination Date</th>
                                <th scope="col">Next Vaccination Date</th>
                            </tr>
                        </thead>
                        <tbody id="vaccineRecordsBody">
                        <!-- Dynamic rows will be added here -->
                        </tbody>
                    </table>
                </div>

                <div class="card-body mt-5">
                    <!--Invoice pdf-->
                    <h3 class="card-title d-flex justify-content-between align-items-center">Invoice
                        <input type="hidden" id="icNo_invoice" value="<?php echo htmlspecialchars($row['icNo']); ?>">
                        <input type="hidden" id="email_invoice" value="<?php echo htmlspecialchars($email); ?>">
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
        </div>

        

    </body>
</html>