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
    $query = "SELECT * FROM category";
    $result = $conn->query($query);

    //Save data into database
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $itemCode = isset($_POST['itemCodeInput']) ? $_POST['itemCodeInput'] : '';
        $itemName = isset($_POST['itemNameInput']) ? $_POST['itemNameInput'] : '';
        $description = isset($_POST['itemDescriptionInput']) ? $_POST['itemDescriptionInput'] : '';
        $category = isset($_POST['itemCategoryInput']) ? $_POST['itemCategoryInput'] : '';
        $quantity = isset($_POST['itemQuantityInput']) ? $_POST['itemQuantityInput'] : '';
        $expiryDate = isset($_POST['itemExpiryDate']) ? $_POST['itemExpiryDate'] : '';
        $reorderThreshold = isset($_POST['itemReorderThreshold']) ? $_POST['itemReorderThreshold'] : '';

        $sql = "INSERT INTO inventory (itemCode, itemName, description, quantity, expiryDate, reorder_threshold, categoryID) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssisii", $itemCode, $itemName, $description, $quantity, $expiryDate, $reorderThreshold, $category);

        if ($stmt->execute()) {
            echo "<script> 
                    alert('Inventory record has been added successfully!');
                    window.location.href = 'inventory.php';
                </script>";
        }else{
            echo "<script>
                    alert('Error: " . $stmt->error . "');
                </script>";
        }

        $stmt->close();
    }

    $conn->close();
?>

        <div class="flex-grow-1 p-3" style="overflow-y: auto; margin-top: -20px; margin-left: auto;">
            <div class="container-fluid">
                <div class="row">
                    <!-- Ensure the main content area takes the remaining space after the 280px sidebar -->
                    <div class="col-md-9 col-lg-10 px-md-4">
                        
                        <h1 class="display-5 fw-bold text-body-emphasis">Create New Inventory</h1>
                        
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="../Admin Homepage/homepage.php">Home</a></li>
                                <li class="breadcrumb-item"><a href="../Admin Inventory Page/inventory.php">Inventory</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Create New Inventory</li>
                            </ol>
                        </nav>

                        <form action= "" method="POST">
                            <div class="form">
                                <!-- Item Code Section -->
                                <div class="row">
                                    <label for="itemCodeInput" class="col-sm-2 col-form-label">Item Code</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="itemCodeInput" name="itemCodeInput" required>
                                    </div>
                                </div>
                                <!-- Item Name Section -->
                                <div class="row mt-4">
                                    <label for="itemNameInput" class="col-sm-2 col-form-label">Item Name</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="itemNameInput" name="itemNameInput" required>
                                    </div>
                                </div>
                                <!-- Item Description Section -->
                                <div class="row mt-4">
                                    <label for="itemDescriptionInput" class="col-sm-2 col-form-label">Description</label>
                                    <div class="col-sm-10">
                                        <textarea class="form-control" id="itemDescriptionInput" name="itemDescriptionInput" required></textarea>
                                    </div>
                                </div>
                                <!-- Item Category Section -->
                                <div class="row mt-4"> 
                                    <label for="itemCategoryInput" class="col-sm-2 col-form-label">Category</label>
                                    <div class="col-sm-10">
                                        <select class="form-select" id="itemCategoryInput" name="itemCategoryInput">
                                        <?php
                                            if($result->num_rows > 0){
                                                while($row = $result->fetch_assoc()){
                                                    echo '<option value="'.$row['categoryID'].'">'.$row['categoryName'].'</option>';
                                                }
                                            }else{
                                                echo '<option>No category found</option>';
                                            }
                                        ?>
                                        </select>
                                    </div>
                                </div> 
                                <!-- Item Quantity Section -->
                                <div class="row mt-4">
                                    <label for="itemQuantityInput" class="col-sm-2 col-form-label">Stock Quantity</label>
                                    <div class="col-sm-10">
                                        <input type="number" class="form-control" id="itemQuantityInput" name="itemQuantityInput" min="0" required>
                                    </div>
                                </div>
                                <!-- Expiry Date Section -->
                                <div class="row mt-4">
                                    <label for="itemExpiryDate" class="col-sm-2 col-form-label">Expiry Date</label>
                                    <div class="col-sm-10">
                                        <input type="date" class="form-control" id="itemExpiryDate" name="itemExpiryDate" required>
                                    </div>
                                </div>
                                <!-- Item Reorder Quantity Section -->
                                <div class="row mt-4">
                                    <label for="itemReorderThreshold" class="col-sm-2 col-form-label">Reorder Threshold</label>
                                    <div class="col-sm-10">
                                        <input type="number" class="form-control" id="itemReorderThreshold" name="itemReorderThreshold" min="0" required>
                                    </div>
                                </div>
                                <!--Back/Save Button-->
                                <div class="mb-3 text-center" style="margin-top: 50px">
                                    <button type="button" class="btn btn-secondary me-3" onclick="goBackInventory()">Back</button>
                                    <button type="submit" class="btn btn-primary">Add</button>
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