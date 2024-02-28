<?php
    //Include database
    include '../db_conn.php';

    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        // Extract the data from the POST request
        $itemCode = $_POST['itemCode'];
        $itemName = $_POST['itemName'];
        $description = $_POST['description'];
        $category = $_POST['category'];
        $quantity = $_POST['quantity'];
        $expiryDate = $_POST['expiryDate'];
        $reorderThreshold = $_POST['reorderThreshold'];

        $stmt = $conn->prepare("UPDATE inventory i JOIN category c ON i.categoryID = c.categoryID SET i.itemCode = ?, i.itemName = ?, i.description = ?, i.quantity = ?, i.expiryDate = ?, i.reorder_threshold = ?, c.categoryName = ? WHERE i.itemCode = ?");

        $stmt->bind_param("sssisiss", $itemCode, $itemName, $description, $quantity, $expiryDate, $reorderThreshold, $category, $itemCode);

        if ($stmt->execute()) {
            echo "Record updated successfully";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    }

    //Close connection
    $conn->close();

?>
