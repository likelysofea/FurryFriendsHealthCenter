<?php
    include '../db_conn.php';

    $output2 = '';
    $query = "SELECT p.recordID, p.petName, s.speciesName, c.name FROM pet_record p JOIN species s ON p.speciesID = s.speciesID JOIN customer c ON p.icNo = c.icNo WHERE 1=1 "; 

    $searchTerm = isset($_POST["query"]) ? mysqli_real_escape_string($conn, $_POST["query"]) : '';
    $speciesFilter = isset($_POST["filter"]) ? mysqli_real_escape_string($conn, $_POST["filter"]) : '';

    if(!empty($searchTerm)){
        $query .= "AND (p.recordID LIKE '%".$searchTerm."%'
        OR p.petName LIKE '%".$searchTerm."%'
        OR c.name LIKE '%".$searchTerm."%') ";
    }

    if(!empty($speciesFilter) && $speciesFilter !== "None"){  
        $query .= "AND s.speciesID = '".$speciesFilter."' ";
    }

    $query .= " ORDER BY p.recordID DESC";

    $result3 = mysqli_query($conn, $query);

    while ($record = mysqli_fetch_assoc($result3)){
        // Constructing the table row
        $output2 .= '<tr>';
        $output2 .= '<td>' . $record['recordID'] . '</td>';
        $output2 .= '<td>' . $record['petName'] . '</td>';
        $output2 .= '<td>' . $record['speciesName'] . '</td>';
        $output2 .= '<td>' . $record['name'] . '</td>';
        $output2 .= '<td><a href="../Admin Pet Record Page/view_record.php?id=' . $record['recordID'] . '" class="btn btn-primary"><i class="bi bi-eye"></i> View</a></td>';
        $output2 .= '</tr>';
    }

    echo $output2;

 ?>