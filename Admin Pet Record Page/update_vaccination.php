<?php

    include '../db_conn.php';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Assuming you've validated and sanitized the inputs
        $recordId = isset($_POST['recordId']) ? $_POST['recordId'] : null;
        $newNextVaccinationDate = isset($_POST['nextVaccinationDate']) ? $_POST['nextVaccinationDate'] : null;
    
        // Check if the inputs are not empty
        if ($recordId && $newNextVaccinationDate) {
            // Prepare the SQL statement to prevent SQL injection
            $stmt = $conn->prepare("UPDATE vaccination SET nextVaccinationDate = ? WHERE id = ?");
            $stmt->bind_param("si", $newNextVaccinationDate, $recordId);
    
            // Execute the statement
            if ($stmt->execute()) {
                echo "The next vaccination date has been updated successfully.";
            } else {
                echo "Error updating next vaccination date: " . $stmt->error;
            }
    
            // Close the statement
            $stmt->close();
        } else {
            echo "Invalid input";
        }
    } else {
        echo "Invalid request method";
    }
    
    $conn->close();

?>