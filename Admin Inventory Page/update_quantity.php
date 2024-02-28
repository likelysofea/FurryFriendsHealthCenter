<?php
include '../db_conn.php'; // Include your database connection script

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $itemCode = $_POST['itemCode'];
    $newQuantity = $_POST['newQuantity'];

    // Prepare an update statement
    $stmt = $conn->prepare("UPDATE inventory SET quantity = ? WHERE itemCode = ?");
    $stmt->bind_param("is", $newQuantity, $itemCode);

    // Execute the statement
    if ($stmt->execute()) {
        echo "alert('Quantity updated to " . $newQuantity . "!');";
    } else {
        echo "alert('Error updating record: " . $conn->error . "');";
    }

    // Close the statement
    $stmt->close();
}
?>
