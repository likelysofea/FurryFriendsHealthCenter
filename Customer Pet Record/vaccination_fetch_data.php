<?php
    include '../db_conn.php';

    // Obtain the recordID from the URL parameters
    $recordID = isset($_GET['id']) ? $_GET['id'] : null;

    // Handle the case where recordID is not provided or is invalid
    if (!$recordID) {
        die('Record ID is required.');
    }

    $sql = "SELECT * FROM vaccination WHERE recordID = ?"; // Modify as needed

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $recordID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $rows = '';
    $number = 1;
    while ($record = mysqli_fetch_assoc($result)) {

        // Format vaccinationDate
        $vaccinationDateObject = DateTime::createFromFormat('Y-m-d', $record['vaccinationDate']);
        if ($vaccinationDateObject !== false) {
            $vaccinationDateFormatted = $vaccinationDateObject->format('d-m-Y');
        } else {
            $vaccinationDateFormatted = $record['vaccinationDate']; // Keep the original format if conversion fails
        }

        // Format nextVaccinationDate
        $nextVaccinationDateObject = DateTime::createFromFormat('Y-m-d', $record['nextVaccinationDate']);
        if ($nextVaccinationDateObject !== false) {
            $nextVaccinationDateFormatted = $nextVaccinationDateObject->format('d-m-Y');
        } else {
            $nextVaccinationDateFormatted = $record['nextVaccinationDate']; // Keep the original format if conversion fails
        }

        $rows .= "<tr>
                    <th scope='row'>{$number}</th>
                    <td>{$record['vaccineType']}</td>
                    <td>{$vaccinationDateFormatted}</td>
                    <td>{$nextVaccinationDateFormatted}</td>
                </tr>";
        $number++;
    }

    echo $rows; // Output the rows as a string of HTML

    mysqli_stmt_close($stmt);

    $conn->close();

?>