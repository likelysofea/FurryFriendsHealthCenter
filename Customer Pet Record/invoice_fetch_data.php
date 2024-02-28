<?php 
    include '../db_conn.php';

    $icNo = $_GET['icNo'];
    $email = $_GET['email'];

    // Use placeholders for both icNo and email in your query
    $query = "SELECT r.receiptID, r.icNo, r.date, r.totalAmount, p.method, c.email 
            FROM receipt r 
            JOIN payment_method p ON r.paymentMethodID = p.paymentMethodID 
            JOIN customer c ON r.icNo = ? 
            WHERE c.email = ?";
    $stmt = $conn->prepare($query);

    // Bind both icNo and email parameters
    $stmt->bind_param("ss", $icNo, $email); // Assuming both icNo and email are strings

    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {

        //Change date format
        $dateOriginal = $row['date'];
        $dateObject = DateTime::createFromFormat('Y-m-d', $dateOriginal);
        $dateFormatted = $dateObject->format('d-m-Y');

        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['receiptID']) . "</td>";
        echo "<td>" . htmlspecialchars($dateFormatted) . "</td>";
        echo "<td>" . htmlspecialchars($row['totalAmount']) . "</td>";
        echo "<td>" . htmlspecialchars($row['method']) . "</td>";
        echo "<td><a id='printBtn' href='../Customer Pet Record/generate_pdf.php?invoiceNo=" . urlencode($row['receiptID']) . "' class='btn btn-outline-primary me-3' target='_blank'>Print</a></td>";
        echo "</tr>";
    }
    
    // Close the database connection
    $conn->close();
?>