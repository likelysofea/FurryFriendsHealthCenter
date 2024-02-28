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

    //Save data to database
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $petName = $_POST["petName"];
        $species = $_POST["species"];
        $gender = $_POST["gender"];
        $age = $_POST["age"];
        $weight = $_POST["weight"];
        $petImage = "../Profile Picture/" . $_POST["petImage"];
        $ownerName = $_POST["ownerName"];
        $icNo = $_POST["icNo"];
        $phoneNo = $_POST["phoneNo"];
        $email = $_POST["email"];
        $address = $_POST["address"];

        // Check if customer already exists
        $stmt = $conn->prepare("SELECT icNo FROM customer WHERE icNo = ?");
        $stmt->bind_param("s", $icNo);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close(); // Close the statement

        // If the customer does NOT exist, insert new customer
        if ($result->num_rows == 0) {
            $stmt = $conn->prepare("INSERT INTO customer (icNo, name, email, phoneNo, address) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $icNo, $ownerName, $email, $phoneNo, $address);
            if(!$stmt->execute()){
                echo "<script> alert('Error inserting into customer: " . $stmt->error . "'); </script>";
                $stmt->close(); // Close the statement
                return; // Stop the script if the customer insertion fails
            }
            $stmt->close(); // Close the statement
        }

        // Now, insert the pet details into the pets table.
        // This happens whether or not we just added a new customer.


        $stmt = $conn->prepare("INSERT INTO pet_record (icNo, petName, speciesID, age, gender, weight, petImage) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssiisis", $icNo, $petName, $species, $age, $gender, $weight, $petImage);

        if ($stmt->execute()) {
            echo "<script>
                alert('Record added successfully!');
                window.location.href = 'pet_record.php';
            </script>";
        } else {
            echo "<script> alert('Error inserting into pets: " . $stmt->error . "'); </script>";
        }

        $stmt->close(); // Close the statement
            }

    //Close connection
    $conn->close();
?>

        <div class="flex-grow-1 p-3" style="overflow-y: auto; margin-top: -20px; margin-left: auto;">
            <div class="container-fluid">
                <div class="row">
                    <!-- Ensure the main content area takes the remaining space after the 280px sidebar -->
                    <div class="col-md-9 col-lg-10 px-md-4">
                        
                        <h1 class="display-5 fw-bold text-body-emphasis">Create New Pet Record</h1>
                        
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="../Admin Homepage/homepage.php">Home</a></li>
                                <li class="breadcrumb-item"><a href="../Admin Pet Record Page/pet_record.php">Pet Records</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Create New Pet Record</li>
                            </ol>
                        </nav>

                        <form action= "" method="POST">
                            <!-- Pet Information -->
                            <h4 class="mb-4">Pet Information</h4>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="petName" class="form-label">Pet Name</label>
                                    <input type="text" class="form-control" id="petName" name="petName">
                                </div>
                                <div class="col-md-4">
                                    <label for="species" class="form-label">Species</label>
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
                                <div class="col-md-4">
                                    <label for="gender" class="form-label">Gender</label>
                                    <select class="form-select" id="gender" name="gender">
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="age" class="form-label">Age</label>
                                    <input type="text" class="form-control" id="age" name="age">
                                </div>
                                <div class="col-md-4">
                                    <label for="weight" class="form-label">Weight (kg)</label>
                                    <input type="text" class="form-control" id="weight" name="weight">
                                </div>
                                <div class="col-md-4">
                                    <label for="petImage" class="form-label">Pet Picture (optional)</label>
                                    <input class="form-control" type="file" id="petImage" name="petImage">
                                </div>
                            </div>

                            <!-- Owner Information -->
                            <h4 class="mb-4" style="margin-top: 50px";>Owner Information</h4>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="ownerName" class="form-label">Owner Name</label>
                                    <input type="text" class="form-control" id="ownerName" name="ownerName">
                                </div>
                                <div class="col-md-6">
                                    <label for="icNo" class="form-label">IC No</label>
                                    <input type="text" class="form-control" id="icNo" placeholder="xxxxxx-xx-xxxx" name="icNo">
                                    <div id="icNoInvalidFeedback" style="color:red; display:none;">Invalid IC number format!</div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="phoneNo" class="form-label">Phone No</label>
                                    <input type="text" class="form-control" id="phoneNo" name="phoneNo">
                                </div>
                                <div class="col-md-6">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label for="address" class="form-label">Address</label>
                                    <input type="text" class="form-control" id="address" name="address">
                                </div>
                            </div>

                            <!-- Save Button -->
                            <div class="mb-3 text-center" style="margin-top: 50px">
                                <button type="button" class="btn btn-secondary me-3" onclick="goBackCreatePetRecord()">Back</button>
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>

                        </form>

                    </div>
                </div>
            </div>
        </div>

</main>
    </body>    
</html>