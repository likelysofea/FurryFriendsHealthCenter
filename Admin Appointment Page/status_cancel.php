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
                $mail->Subject = 'Appointment Cancellation Notification';
                $mail->Body = '
                                <html>
                                <head>
                                <title>Appointment Cancellation</title>
                                </head>
                                <body>
                                <p>I regret to inform you that due to unforeseen circumstances, we must cancel the appointment that was scheduled for <strong>' . $date . '</strong> at <strong>' . $time . '</strong>.</p>
                                <p>If you still wish to meet with us, we would be delighted to assist you in rescheduling your appointment at a time that suits you better.</p>
                                <p>Please contact us at <a href="tel:06-12345678">06-12345678</a> to arrange a new appointment.</p>
                                <p>Again, we apologize for any inconvenience this cancellation may have caused.</p>
                                <p>Thank you for your understanding and cooperation.</p>
                                <p>Sincerely,<br>
                                Furry Friends Health Center<br>
                                <a href="tel:06-12345678">06-12345678</a></p>
                                </body>
                                </html>';
                
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