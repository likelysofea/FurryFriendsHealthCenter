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

    if (isset($_GET['itemCode']) && !empty($_GET['itemCode'])){
        
        $itemCode = $_GET['itemCode'];
        $itemCode = mysqli_real_escape_string($conn, $itemCode);

        $viewInventory_query = "SELECT i.itemName, i.description, i.quantity, i.expiryDate, i.reorder_threshold, c.categoryName FROM inventory i JOIN category c on i.categoryID = c.categoryID WHERE i.itemCode = '$itemCode'";

        $viewInventory_result = mysqli_query($conn, $viewInventory_query);

        if ($viewInventory_result->num_rows > 0){
            $row = $viewInventory_result->fetch_assoc();
        }
    }

?>

        <div class="flex-grow-1 p-3" style="overflow-y: auto; margin-top: -20px; margin-left: auto;">
            <div class="container-fluid">
                <div class="row">
                    <!-- Ensure the main content area takes the remaining space after the 280px sidebar -->
                    <div class="col-md-9 col-lg-10 px-md-4">
                        
                        <h1 class="display-5 fw-bold text-body-emphasis">View Inventory</h1>
                        
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="../Admin Homepage/homepage.php">Home</a></li>
                                <li class="breadcrumb-item"><a href="../Admin Inventory Page/inventory.php">Inventory</a></li>
                                <li class="breadcrumb-item active" aria-current="page">View Inventory</li>
                            </ol>
                        </nav>

                        <form action= "" method="POST" id="inventory-form">
                            <div class="form">
                                <!-- Item Code Section -->
                                <div class="row">
                                    <label for="itemCodeField" class="col-sm-2 col-form-label">Item Code</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="itemCodeField" name="itemCodeField" value="<?php echo isset($itemCode) ? htmlspecialchars($itemCode) : ''; ?>" disabled readonly>
                                    </div>
                                </div>
                                <!-- Item Name Section -->
                                <div class="row mt-4">
                                    <label for="itemNameField" class="col-sm-2 col-form-label">Item Name</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="itemNameField" name="itemNameField" value="<?php echo isset($row['itemName']) ? htmlspecialchars($row['itemName']) : ''; ?>" disabled>
                                    </div>
                                </div>
                                <!-- Item Description Section -->
                                <div class="row mt-4">
                                    <label for="itemDescriptionField" class="col-sm-2 col-form-label">Description</label>
                                    <div class="col-sm-10">
                                        <textarea class="form-control" id="itemDescriptionField" name="itemDescriptionField" disabled><?php echo isset($row['description']) ? htmlspecialchars($row['description']) : ''; ?> </textarea>
                                    </div>
                                </div>
                                <!-- Item Category Section -->
                                <div class="row mt-4"> 
                                    <label for="itemCategoryField" class="col-sm-2 col-form-label">Category</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="itemCategoryField" name="itemCategoryField" value="<?php echo isset($row['categoryName']) ? htmlspecialchars($row['categoryName']) : ''; ?>" disabled readonly>
                                    </div>
                                </div> 
                                <!-- Item Quantity Section -->
                                <div class="row mt-4">
                                    <label for="itemQuantityField" class="col-sm-2 col-form-label">Stock Quantity</label>
                                    <div class="col-sm-10">
                                        <input type="number" class="form-control" id="itemQuantityField" name="itemQuantityField" value="<?php echo isset($row['quantity']) ? htmlspecialchars($row['quantity']) : ''; ?>" min="0" disabled>
                                    </div>
                                </div>
                                <!-- Expiry Date Section -->
                                <div class="row mt-4">
                                    <label for="expiryDateField" class="col-sm-2 col-form-label">Expiry Date</label>
                                    <div class="col-sm-10">
                                        <input type="date" class="form-control" id="expiryDateField" name="expiryDateField" value="<?php echo isset($row['expiryDate']) ? htmlspecialchars($row['expiryDate']) : ''; ?>" disabled>
                                    </div>
                                </div>
                                <!-- Item Reorder Quantity Section -->
                                <div class="row mt-4">
                                    <label for="reorderThresholdField" class="col-sm-2 col-form-label">Reorder Threshold</label>
                                    <div class="col-sm-10">
                                        <input type="number" class="form-control" id="reorderThresholdField" name="reorderThresholdField" value="<?php echo isset($row['reorder_threshold']) ? htmlspecialchars($row['reorder_threshold']) : ''; ?>" min="0" disabled>
                                    </div>
                                </div>
                                <!--Back/Edit/Delete Buttons-->
                                <div class="mb-3 text-center" style="margin-top: 50px">
                                    <a id="backBtn" class="btn btn-secondary me-3" onclick="goBack()">Back</a>
                                    <a id="editBtn" class="btn btn-warning me-3">Edit</a>
                                    <a id="saveBtn" class="btn btn-primary me-3" style="display: none;">Save</a>
                                    <a id="deleteBtn" class="btn btn-danger">Delete</a>
                                </div>
                            </div>
                        </form>

                        <!-- Bootstrap Modal -->
                        <div class="modal fade" id="inventoryStatusModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="modalLabel">Update Status</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <!-- The server response will be inserted here -->
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