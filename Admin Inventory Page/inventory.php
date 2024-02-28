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

    $inventoryQuery = "SELECT i.itemCode, i.itemName, c.categoryName, i.quantity FROM inventory i JOIN category c ON i.categoryID = c.categoryID";
    $inventoryResult = mysqli_query($conn, $inventoryQuery);
?>

        <div class="flex-grow-1 p-3" style="overflow-y: auto; margin-top: -20px;">
            <div class="container-fluid">
                <div class="row">
                    <!-- Ensure the main content area takes the remaining space after the 280px sidebar -->
                    <div class="col-md-9 col-lg-10 px-md-4">

                        <h1 class="display-5 fw-bold text-body-emphasis">Inventory</h1>

                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="../Admin Homepage/homepage.php">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Inventory</li>
                            </ol>
                        </nav> 

                        <div class="search-filter-section d-flex align-items-center justify-content-between mt-5" style="max-width: 1200px; margin: 30 auto; ">
                            <div class="d-flex align-items-center">
                                <input class="form-control form-control-lg inventory_search me-6" type="text" placeholder="Search...">
                                <div class="filter d-flex align-items-center">
                                    <label class="col-sm-2 col-form-label col-form-label-lg me-3">Filter:</label>
                                    <select class="form-select form-select-lg" id="inventory_filter">
                                        <option selected>None</option>
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
                                <a href="../Admin Inventory Page/create_inventory.php">
                                    <button type="button" class="btn btn-primary btn-lg"><i class="bi bi-plus-lg"></i></button>
                                </a>
                            </div>
                        </div> 
                        
                        <div class="row mt-5">
                            <div class="col">
                                <table id="inventory-table" class="table custom-margin">
                                    <thead>
                                        <tr>
                                            <th scope="col">Item Code</th>
                                            <th scope="col">Item Name</th>
                                            <th scope="col">Category</th>
                                            <th scope="col">Quantity</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($record = mysqli_fetch_assoc($inventoryResult)): ?>
                                            <tr> 
                                                <td class="item-code"><?php echo htmlspecialchars($record['itemCode']); ?></td>
                                                <td><?php echo htmlspecialchars($record['itemName']); ?></td>
                                                <td><?php echo htmlspecialchars($record['categoryName']); ?></td>
                                                <td>
                                                    <div class="quantity-counter" data-item-code="<?php echo htmlspecialchars($record['itemCode']); ?>">
                                                        <button type="button" class="btn-decrement btn btn-outline-secondary btn-sm"><i class="bi bi-dash"></i></button>
                                                        <input type="text" class="form-control quantity-input" value="<?php echo htmlspecialchars($record['quantity']); ?>" readonly>
                                                        <button type="button" class="btn-increment btn btn-outline-secondary btn-sm"><i class="bi bi-plus"></i></button>
                                                    </div>
                                                </td>
                                                <td>
                                                    <a href="../Admin Inventory Page/view_inventory.php?itemCode=<?php echo urlencode($record['itemCode']); ?>" class="btn btn-primary btn-sm">
                                                        <i class='bi bi-eye'></i> 
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        <!-- Restock Alert Modal -->
                        <div class="modal fade" id="restockAlertModal" tabindex="-1" aria-labelledby="restockAlertModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="restockAlertModalLabel">Restock Alert</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <!-- The message will be inserted here -->
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                        
                        <!-- Edit Inventory Modal -->
                        <div class="modal fade" id="editInventoryModal" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Inventory Item</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form id="editInventoryForm">
                                            <input type="hidden" id="editItemCode" name="itemCode">
                                            <div class="mb-3">
                                                <label for="editItemName" class="form-label">Item Name</label>
                                                <input type="text" class="form-control" id="editItemName" name="itemName" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="editItemDescription" class="form-label">Description</label>
                                                <textarea class="form-control" id="editItemDescription" name="editItemDescription" required></textarea>
                                            </div>
                                            <div class="mb-3">
                                                <label for="editItemCategory" class="form-label">Category</label>
                                                <select class="form-select" id="editItemCategory" name="editItemCategory">
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
                                            <div class="mb-3">
                                                <label for="editItemQuantity" class="form-label">Stock Quantity</label>
                                                <input type="number" class="form-control" id="editItemQuantity" name="editItemQuantity" min="0" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="editExpiryDate" class="col-sm-2 col-form-label">Expiry Date</label>
                                                <input type="date" class="form-control" id="editExpiryDate" name="editExpiryDate" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="editReorderThreshold" class="col-sm-2 col-form-label">Reorder Threshold</label>
                                                <input type="number" class="form-control" id="editReorderThreshold" name="editReorderThreshold" min="0" required>
                                            </div>
                                            <!-- Include other fields as necessary -->
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary">Save Changes</button>
                                            </div>
                                        </form>
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

