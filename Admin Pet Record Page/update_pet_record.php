<?php
    //Include database
    include '../db_conn.php';

    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        // Extract the data from the POST request
        $recordID = $_POST['recordID'];
        $petName = $_POST['petName'];
        $gender = $_POST['gender'];
        $age = $_POST['age'];
        $weight = $_POST['weight'];
        $ownerName = $_POST['name'];
        $phoneNo = $_POST['phoneNo'];
        $email = $_POST['email'];
        $address = $_POST['address'];

        $stmt = $conn->prepare("UPDATE pet_record p JOIN customer c ON p.icNo = c.icNo SET p.petName = ?, p.gender = ?, p.age = ?, p.weight = ?, c.name = ?, c.phoneNo = ?, c.email = ?, c.address = ? WHERE p.recordID = ?");

        $stmt->bind_param("ssiissssi", $petName, $gender, $age, $weight, $ownerName, $phoneNo, $email, $address, $recordID);

        if ($stmt->execute()) {
            echo "Record updated successfully";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    }

    //Close connection
    $conn->close();

?>
