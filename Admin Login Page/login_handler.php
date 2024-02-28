<?php
    session_start();

    require_once '../db_conn.php';

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $username = $_POST["username"];
        $password = $_POST["password"];

        $sql = "SELECT id, username, password FROM admin WHERE BINARY username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if($row = $result->fetch_assoc()){
            // Check if the entered username matches exactly, including case
            if($username !== $row["username"]){
                // Username does not match exactly
                header("Location: ../Admin Login Page/?error=username");
                exit;
            }

            // Verify the password against the hashed password in the database
            if(password_verify($password, $row["password"])){
                // Password is correct, set the session variable
                $_SESSION["userID"] = $row["id"];
                $_SESSION["username"] = $username;

                // Redirect to homepage
                header("Location:../Admin Homepage/homepage.php");
                exit;
            }
            else{
                // Password is incorrect
                header("Location: ../Admin Login Page/?error=password");
                exit;
            }       
        }
        else{
            // Username doesn't exist in database
            header("Location: ../Admin Login Page/?error=username");
            exit;
        }
    }
?>
