<?php
    include '../db_conn.php';
    require '../config.php';

    // Import PHPMailer classes
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    if (isset($_POST['id']) && isset($_POST['status'])) {
        $appointmentId = $_POST['id'];
        $newStatus = $_POST['status'];
        $email = $_POST['email'];
        $name = $_POST['name'];
        $date = $_POST['date'];
        $time = $_POST['time'];

        $stmt = $conn->prepare("UPDATE appointment SET statusID = ? WHERE appointmentID = ?");
        $stmt->bind_param("ii", $newStatus, $appointmentId);

        if ($stmt->execute()) {

            // Try to send a confirmation email using PHPMailer
            try{
                require '../PHPMailer/src/Exception.php';
                require '../PHPMailer/src/PHPMailer.php';
                require '../PHPMailer/src/SMTP.php';

                $mail = new PHPMailer(true);
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = $email_username;
                $mail->Password = $email_password;
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;

                //Recipients
                $mail->setFrom('cupcakealiesya@gmail.com', 'Vet Clinic');
                $mail->addAddress($email, $name);

                // Content
                $mail->isHTML(true); // Set email format to HTML
                $mail->Subject = 'Appointment Confirmation Notification';
                $mail->Body = "
                                <html>
                                <head>
                                <title>Appointment Confirmation</title>
                                </head>
                                <body>
                                <p>We're excited to confirm your appointment:</p>

                                <ul>
                                    <li>Date: <strong>{$date}</strong></li>
                                    <li>Time: <strong>{$time}</strong></li>
                                </ul>

                                <p>We can't wait to meet you! If you have any questions or need to make changes, feel free to reach out. We're here to help.</p>

                                <p>See you soon!</p>

                                <p>Best regards,<br>
                                Furry Friends Health Center<br>
                                <a href='tel:06-12345678'>06-12345678</a></p>
                                </body>
                                </html>";
                
                $mail->send();

                echo json_encode(['success' => true, 'message' => 'Appointment saved and email has been sent successfully!']);

            } catch (Exception $e){
                echo json_encode(['success' => false, 'message' => 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo]);
            }

        }else {
            // Handle execution error
            echo json_encode(['success' => false, 'message' => 'Error: ' . $stmt->error]);
        }
        $stmt->close();
        $conn->close();
    }
?>