<?php
    session_start();
    include '../db_conn.php'; // Your database connection file

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nickname = mysqli_real_escape_string($conn, $_POST['nickname']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);

        // Check if email already exists
        $stmt = $conn->prepare("SELECT email FROM login WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            // Email already exists
            echo "Email is already registered";
        } else {
            // Email doesn't exist, insert new user
            $hashed_password = password_hash($password, PASSWORD_DEFAULT); // Hash the password
            $insert = $conn->prepare("INSERT INTO login (nickname, email, password) VALUES (?, ?, ?)");
            $insert->bind_param("sss", $nickname, $email, $hashed_password);
            $insert->execute();
            
            if($insert->affected_rows === 1){
                echo "success";
            } else {
                echo "An error occurred. Please try again.";
            }
        }

        $stmt->close();
        $conn->close();
    }
?>
