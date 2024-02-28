<?php
    session_start(); 

    include '../db_conn.php'; 
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get email and password from POST request
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);

        // Prepare the SQL statement to prevent SQL injection
        $stmt = $conn->prepare("SELECT * FROM login WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['password'])) {
                // Password is correct, start the session
                $_SESSION['loginID'] = $user['loginID']; 
                $_SESSION['email'] = $user['email']; 
                echo "success";
            } else {
                // Invalid password
                echo "Invalid password";
            }
        } else {
            // Email not found
            echo "The email address is not registered";
        }

        $stmt->close();
        $conn->close();
    }
?>
