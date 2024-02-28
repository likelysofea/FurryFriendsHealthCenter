<?php
    include '../db_conn.php';

    $output = '';
    $query = "SELECT a.appointmentID, a.date, a.time, a.name, s.speciesName, a.reason, st.statusName FROM appointment a 
        JOIN species s ON a.speciesID = s.speciesID 
        JOIN status st ON a.statusID = st.statusID
        WHERE 1=1 ";  // this just simplifies appending additional WHERE conditions

    // Fetch and sanitize the search query and filter values from POST
    $search = isset($_POST["query"]) ? mysqli_real_escape_string($conn, $_POST["query"]) : '';
    $filter = isset($_POST["filter"]) ? mysqli_real_escape_string($conn, $_POST["filter"]) : '';

    if(!empty($search)){
        $query .= "AND (a.name LIKE '%".$search."%'
        OR a.date LIKE '%".$search."%'
        OR s.speciesName LIKE '%".$search."%'
        OR st.statusName LIKE '%".$search."%') ";
    }

    if(!empty($filter) && $filter !== "None"){  
        $query .= "AND st.statusID = '".$filter."' ";
    }

    // Add ORDER BY clause here
    $query .= "ORDER BY a.date DESC, a.time DESC";


    $result = mysqli_query($conn, $query);

    while ($appointment = mysqli_fetch_assoc($result)){
        
        // Determine the appropriate class based on status
        $statusClass = '';
        
        switch ($appointment['statusName']) {
            case 'Pending':
                $statusStyle = 'color: #ffc107; font-weight: bold;';
                break;
            case 'Confirmed':
                $statusStyle = 'color: #28a745; font-weight: bold;';
                break;
            case 'Cancelled':
                $statusStyle = 'color: #dc3545; font-weight: bold;';
                break;
        }

        // Constructing the table row
        $output .= '<tr>';
        $output .= '<td>' . $appointment['name'] . '</td>';

        $output .= '<td>';
        $originalDate = $appointment['date'];
        $timestamp = strtotime($originalDate);
        $formattedDate = date("d-m-Y", $timestamp);
        $output .= $formattedDate;
        $output .= '</td>';

        $output .= '<td>' . $appointment['time'] . '</td>';
        $output .= '<td>' . $appointment['speciesName'] . '</td>';
        $output .= '<td>' . $appointment['reason'] . '</td>';
        $output .= '<td><span style="' . $statusStyle . '">' . $appointment['statusName'] . '</span></td>';
        $output .= '<td><a href="../Admin Appointment Page/view_appointment.php?id=' . $appointment['appointmentID'] . '" class="btn btn-primary"><i class="bi bi-eye"></i> View</a></td>';
        $output .= '</tr>';
    }

    echo $output;
?>


