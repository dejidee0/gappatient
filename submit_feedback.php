<?php
require 'vendor/autoload.php';
require '/Users/ejenamvictor/Desktop/gap_web/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use TCPDF;

header('Content-Type: application/json');

// Instantiate a new PDF document
$pdf = new TCPDF();
$pdf->AddPage();
$pdf->SetFont('helvetica', '', 10);

// Add a logo to the PDF
$logoPath = '/Users/ejenamvictor/Desktop/gap_web/images/logogreen-removebg-preview.png'; // Replace with the path to your logo image
$pdf->Image($logoPath, 15, 10, 40); // Adjust the position and size as needed

// Move the cursor down to start the text after the logo
$pdf->Ln(20);

// Construct PDF content
$questions = [
    'Did we treat you with dignity and respect?', 
    'Were you involved as much as you wanted to be in your care?', 
    'Did our staff ask for your consent in all that they did with you?',
    'Did we provide you with information to enable you make informed decisions about what was being proposed to you during our engagement?', 
    'Did you feel supported by our staff?',
    'Did we treat you with kindness and compassion?', 
    'Did you feel listened to?', 
    'Did you have the opportunity to ask questions?',  
    'Overall Service Rating', 
    'What did we do well?', 
    'What do we need to change to make the experience better and how do we need to change it?'
];

$responses = [
    isset($_POST['q1']) ? $_POST['q1'] : 'No response',
    isset($_POST['q2']) ? $_POST['q2'] : 'No response',
    isset($_POST['q3']) ? $_POST['q3'] : 'No response',
    isset($_POST['q4']) ? $_POST['q4'] : 'No response',
    isset($_POST['q5']) ? $_POST['q5'] : 'No response',
    isset($_POST['q6']) ? $_POST['q6'] : 'No response',
    isset($_POST['q7']) ? $_POST['q7'] : 'No response',
    isset($_POST['q8']) ? $_POST['q8'] : 'No response',
    isset($_POST['overall_rating']) ? $_POST['overall_rating'] : 'No response',
    isset($_POST['feedback_good']) ? $_POST['feedback_good'] : 'No response',
    isset($_POST['feedback_improvement']) ? $_POST['feedback_improvement'] : 'No response'
];

$content = '';

foreach ($questions as $index => $question) {
    $content .= $question . "\nResponse: " . $responses[$index] . "\n\n";
}

$pdf->Write(0, $content);
//$pdfPath = __DIR__ . '/feedback_' . time() . '.pdf'; // Save PDF in the current directory
$pdfPath = '/Users/ejenamvictor/tmp_pdf_storage/feedback_' . time() . '.pdf';
$pdf->Output($pdfPath, 'F'); // Save the file

// Configure and send the email
$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';  // Specify your SMTP servers
    $mail->SMTPAuth = true;
    $mail->Username = 'ejenamvictor76@gmail.com'; // SMTP username
    $mail->Password = 'dnsladphvqaxqiyp'; // SMTP password
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('your_email@example.com', 'Feedback Form');
    $mail->addAddress('recipient@example.com');  // Add a recipient

    $mail->isHTML(true);
    $mail->Subject = 'New Feedback Submission';
    $mail->Body    = 'A new feedback form has been submitted. Please find the attached PDF.';
    $mail->addAttachment($pdfPath);  // Attach the generated PDF

    $mail->send();
    echo json_encode(['message' => 'Feedback submitted successfully.']);
} catch (Exception $e) {
    echo json_encode(['error' => 'Mailer Error: ' . $mail->ErrorInfo]);
}
