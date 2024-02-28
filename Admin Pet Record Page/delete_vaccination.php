<?php
    include '../db_conn.php'; 

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['recordId'])) {
        $recordId = $_POST['recordId'];

        // Prepare the SQL statement to prevent SQL injection
        $stmt = $conn->prepare("DELETE FROM vaccination WHERE id = ?");
        $stmt->bind_param("i", $recordId);

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
