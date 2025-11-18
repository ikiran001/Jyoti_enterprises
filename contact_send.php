<?php
// contact_send.php - mirrors send.php pattern
ini_set('display_errors', 0);
error_reporting(0);
ob_start();

header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Origin: *');

require __DIR__ . '/vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success'=>false,'message'=>'Method not allowed']);
    exit;
}

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$message = trim($_POST['message'] ?? '');

if (!$name || !$email || !$message) {
    http_response_code(400);
    echo json_encode(['success'=>false,'message'=>'All fields are required']);
    exit;
}

$mail = new PHPMailer(true);
try {
    $mail->SMTPDebug = 0;
    $mail->isSMTP();

    // Configure exactly as in send.php
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'kiran.jadhav1993@gmail.com';
    $mail->Password   = 'chsqmasvvlrvgdus';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    $mail->setFrom('no-reply@yourdomain.com','Jyoti Enterprises');
    $mail->addAddress('kiran.jadhav1993@gmail.com');
    $mail->addReplyTo($email, $name);

    $mail->Subject = "Contact form: message from $name";
    $mail->Body = "Name: $name\nEmail: $email\n\nMessage:\n$message";
    $mail->send();

    ob_clean();
    http_response_code(200);
    echo json_encode(['success'=>true,'message'=>'Message sent!']);
    exit;
} catch (Exception $e) {
    ob_clean();
    http_response_code(500);
    echo json_encode(['success'=>false,'message'=>'Mailer Error: '.($mail->ErrorInfo ?: $e->getMessage())]);
    exit;
}
