<?php
header('Access-Control-Allow-Origin: http://127.0.0.1:5500'); // Only allow this origin
ini_set('display_errors', 1);
error_reporting(E_ALL);

require 'vendor/autoload.php';
require_once 'vendor/phpmailer/phpmailer/src/Exception.php';
require_once 'vendor/phpmailer/phpmailer/src/PHPMailer.php';
require_once 'vendor/phpmailer/phpmailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Database credentials and connection
$servername = "localhost";
$username = "root";
$password = "MosesTakunda3@";
$dbname = "gaptransport";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}


// Check if comment POST data is received
if (isset($_POST['comment']) && !empty($_POST['comment']) && isset($_POST['g-recaptcha-response'])) {
    // Collect the user type from the POST request
    $user_type = isset($_POST['user_type']) ? $conn->real_escape_string($_POST['user_type']) : 'none';
    $comment = $conn->real_escape_string($_POST['comment']);
    $email = $conn->real_escape_string($_POST['email']);
    $captcha = $_POST['g-recaptcha-response'];

    // Verify CAPTCHA
    $secretKey = "6LdhOZUpAAAAAKMtlgljrbal4wjddWfk-k-XWLvQ";
    $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$secretKey}&response={$captcha}");
    $responseKeys = json_decode($response, true);

    if (intval($responseKeys["success"]) !== 1) {
        die('CAPTCHA verification failed. Please try again.');
    }

    // Prepare and bind
    $sql = "INSERT INTO comments (comment, email, user_type) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $comment, $email, $user_type);

    // Execute and respond
    if ($stmt->execute()) {
        echo 'Comment successfully saved.';
        $response = ['commentStatus' => 'Comment successfully saved.'];
        // send email...
        // Replace the below credentials with your SMTP credentials.
        $mail = new PHPMailer(true);
        try {
            $mail->SMTPDebug = 0;  // Disable verbose debug output
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'ejenamvictor76@gmail.com';
            $mail->Password = 'dnsladphvqaxqiyp';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('ejenamvictor76@gmail.com', 'Mailer');
            $mail->addReplyTo($email, 'Commenter'); // Commenter's email
            $mail->addAddress($email, 'Joe User'); // Add a recipient

            //Recipients
            $mail->setFrom('ejenamvictor76@gmail.com', 'Mailer');
            $mail->addAddress('ejenamvictor76@gmail.com', 'Recipient Name');     // Add a recipient

            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = 'New Comment Posted';
            $mail->Body    = 'A new comment has been posted: ' . $comment;
            $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            $mail->send();
            
            echo 'Message has been sent';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        echo 'Error saving comment: ' . $stmt->error;
    }
    $stmt->close();
} else {
    echo 'No comment or CAPTCHA response received.';
}

$conn->close();