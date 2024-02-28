<?php
    include '../db_conn.php';

    function getAdminId($conn, $username) {
        $stmt = mysqli_prepare($conn, "SELECT id FROM admin WHERE username = ?");
        mysqli_stmt_bind_param($stmt, 's', $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        return $row['id'] ?? null; // Return the ID or null if not found
    }

    function getCustomerId($conn, $name) {
        $stmt = mysqli_prepare($conn, "SELECT icNo FROM customer WHERE name = ?");
        mysqli_stmt_bind_param($stmt, 's', $name);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        return $row['icNo'] ?? null; // Return the icNo or null if not found
    }

    function insertReceipt($conn, $receiptId, $date, $totalAmount, $adminId, $customerId, $paymentMethodId) {
        // Corrected the number of placeholders and the SQL command
        $stmt = mysqli_prepare($conn, "INSERT INTO receipt (receiptID, date, totalAmount, id, icNo, paymentMethodID) VALUES (?, ?, ?, ?, ?, ?)");
        // Corrected the types of the parameters
        mysqli_stmt_bind_param($stmt, 'isdisi', $receiptId, $date, $totalAmount, $adminId, $customerId, $paymentMethodId);
        mysqli_stmt_execute($stmt);
        // Removed the return of mysqli_insert_id as it's not needed
    }

    function insertReceiptItems($conn, $receiptId, $descriptions, $amounts) {
        foreach ($descriptions as $index => $description) {
            $amount = $amounts[$index];
            $stmt = mysqli_prepare($conn, "INSERT INTO receipt_items (itemDescription, itemPrice, receiptID) VALUES (?, ?, ?)");
            mysqli_stmt_bind_param($stmt, 'sdi', $description, $amount, $receiptId);
            mysqli_stmt_execute($stmt);
        }
    }

    $receiptId = $_POST['invoiceNo'];
    $adminId = getAdminId($conn, $_POST['generatedBy']);
    $customerId = getCustomerId($conn, $_POST['billTo']);
    $paymentMethodId = $_POST['paymentMethod']; // Assuming this is the ID directly
    $date = $_POST['date'];
    $totalAmount = $_POST['totalAmount'];
    $descriptions = $_POST['descriptions'];
    $amounts = $_POST['amounts'];

    // Begin transaction
    mysqli_begin_transaction($conn);

    try {
        insertReceipt($conn, $receiptId, $date, $totalAmount, $adminId, $customerId, $paymentMethodId);
        insertReceiptItems($conn, $receiptId, $descriptions, $amounts);

        // Commit transaction
        mysqli_commit($conn);
        echo "Invoice saved successfully!";
    } catch (Exception $e) {
        // An exception has been thrown
        mysqli_rollback($conn);
        echo "Error: " . $e->getMessage();
    }

    // Close the database connection
    mysqli_close($conn);
?>