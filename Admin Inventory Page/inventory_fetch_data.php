<?php
    include '../db_conn.php';

    $output = '';
    $query = "SELECT i.itemCode, i.itemName, c.categoryName, i.quantity, i.reorder_threshold FROM inventory i JOIN category c ON i.categoryID = c.categoryID WHERE 1=1 "; 

    $searchTerm = isset($_POST["query"]) ? mysqli_real_escape_string($conn, $_POST["query"]) : '';
    $categoryFilter = isset($_POST["filter"]) ? mysqli_real_escape_string($conn, $_POST["filter"]) : '';

    if(!empty($searchTerm)){
        $query .= " AND (i.itemCode LIKE '%".$searchTerm."%'
        OR i.itemName LIKE '%".$searchTerm."%') ";
    }

    if(!empty($categoryFilter) && $categoryFilter !== "None"){  
        $query .= " AND c.categoryID = '".$categoryFilter."' ";
    }

    $result = mysqli_query($conn, $query);

    while ($record = mysqli_fetch_assoc($result)) {
        // Escape the output to prevent XSS attacks
        $itemCode = htmlspecialchars($record['itemCode']);
        $itemName = htmlspecialchars($record['itemName']);
        $categoryName = htmlspecialchars($record['categoryName']);
        $quantity = htmlspecialchars($record['quantity']);
        $reorderThreshold = htmlspecialchars($record['reorder_threshold']);

        $quantityClass = ($quantity <= $reorderThreshold) ? 'text-danger fw-bold' : '';
        $actionButtons = "<a href=\"../Admin Inventory Page/view_inventory.php?itemCode=" . urlencode($itemCode) . "\" class=\"btn btn-primary btn-sm\"><i class='bi bi-eye'></i> View </a>";
    
        // Constructing the table row with a quantity counter
        $output .= '<tr>';
        $output .= '<td class="item-code">' . $itemCode . '</td>';
        $output .= '<td>' . $itemName . '</td>';
        $output .= '<td>' . $categoryName . '</td>';
        $output .= '<td>';
        $output .= '<div class="quantity-counter" data-item-code="' . $itemCode . '" data-reorder-threshold="' . $reorderThreshold . '">';
        $output .= '<button type="button" class="btn-decrement btn btn-outline-secondary btn-sm"><i class="bi bi-dash"></i></button>';
        $output .= '<input type="text" class="form-control quantity-input ' . $quantityClass . '" value="' . $quantity . '" readonly>';
        $output .= '<button type="button" class="btn-increment btn btn-outline-secondary btn-sm"><i class="bi bi-plus"></i></button>';
        $output .= '</div>';
        $output .= '</td>';
        $output .= '<td>' . $actionButtons . '</td>';
        $output .= '</tr>';
    }
    

    echo $output;
?>
