<?php 
    include '../db_conn.php';

    $icNo = $_GET['icNo'];

    $query = "SELECT r.receiptID, r.icNo, r.date, r.totalAmount, p.method FROM receipt r JOIN payment_method p ON r.paymentMethodID = p.paymentMethodID WHERE r.icNo = ? ORDER BY r.date DESC";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $icNo);
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
        echo "<td><a id='printBtn' href='../Admin Invoice Page/generate_pdf.php?invoiceNo=" . urlencode($row['receiptID']) . "' class='btn btn-primary me-3' target='_blank'>Print</a></td>";
        echo "</tr>";
    }
    
    // Close the database connection
    $conn->close();
?>