<?php 

    include '../db_conn.php';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
        $vaccineType = $_POST['vaccineType'];
        $vaccinationDate = $_POST['vaccinationDate'];
        $nextVaccinationDate = $_POST['nextVaccinationDate'];
        $recordID = $_POST['recordID'];
    
        $sql = "INSERT INTO vaccination (vaccineType, vaccinationDate, nextVaccinationDate, recordID) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, 'sssi', $vaccineType, $vaccinationDate, $nextVaccinationDate, $recordID);
    
        if (mysqli_stmt_execute($stmt)) {
            echo 'Record added successfully.';
        } else {
            echo 'Error: ' . mysqli_stmt_error($stmt);
        }
    
        mysqli_stmt_close($stmt);
    } else {
        // If the request method is not POST
        echo 'Method not allowed';
    }

    $conn->close();

?>
