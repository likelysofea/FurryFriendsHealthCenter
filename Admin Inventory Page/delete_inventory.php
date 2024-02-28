<?php
    include '../db_conn.php'; 

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['itemCode'])) {
        $itemCode = $_POST['itemCode'];

        // Prepare the SQL statement to prevent SQL injection
        $stmt = $conn->prepare("DELETE FROM inventory WHERE itemCode = ?");
        $stmt->bind_param("s", $itemCode);

        // Execute the statement
        if ($stmt->execute()) {
            echo "Record deleted successfully";
        } else {
            echo "Error deleting record: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    } else {
        echo "Invalid request";
    }

    $conn->close();

?>
