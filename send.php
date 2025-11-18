<?php
// send.php
// Clean JSON response, PHPMailer SMTP example.
// IMPORTANT: edit SMTP_* placeholders and TO_ADDRESS before using.

ini_set('display_errors', 0);
error_reporting(0);
ob_start();

header('Content-Type: application/json; charset=UTF-8');
// For local testing only. Replace '*' with your domain in production.
header('Access-Control-Allow-Origin: *');

require __DIR__ . '/vendor/autoload.php'; // Composer autoload for PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// sanitize inputs
$name    = trim($_POST['name'] ?? '');
$email   = trim($_POST['email'] ?? '');
$phone   = trim($_POST['phone'] ?? '');
$product = trim($_POST['product'] ?? 'General Enquiry');
$message = trim($_POST['message'] ?? '');

if (!$name || !$email) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Name and email are required']);
    exit;
}

// configure PHPMailer
$mail = new PHPMailer(true);
try {
    $mail->SMTPDebug = 0; // MUST be 0 in production (no debug output)
    $mail->isSMTP();

    // ====== EDIT THESE VALUES ======
    $mail->Host       = 'smtp.gmail.com';        // e.g. smtp.gmail.com or mail.yourdomain.com
    $mail->SMTPAuth   = true;
    $mail->Username   = 'kiran.jadhav1993@gmail.com';   // full email for SMTP auth
    $mail->Password   = 'chsqmasvvlrvgdus';   // password or app password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // or ENCRYPTION_SMTPS
    $mail->Port       = 587;                    // 587 for STARTTLS or 465 for SSL
    // ====== END EDIT ======

    // message headers
    $mail->setFrom('no-reply@yourdomain.com', 'Jyoti Enterprises'); // adjust domain
    $mail->addAddress('kiran.jadhav1993@gmail.com'); // <-- where enquiries should go (change)
    $mail->addReplyTo($email, $name);

    $mail->Subject = 'Website Enquiry: ' . ($product ?: 'General Enquiry');

    $body = "New enquiry from website\n\n";
    $body .= "Name: $name\n";
    $body .= "Email: $email\n";
    $body .= "Phone: $phone\n";
    $body .= "Product: $product\n\n";
    $body .= "Message:\n$message\n";

    $mail->Body = $body;
    $mail->send();

    // Clear any accidental output and return JSON success
    ob_clean();
    http_response_code(200);
    echo json_encode(['success' => true, 'message' => 'Enquiry sent successfully!']);
    exit;
} catch (Exception $e) {
    ob_clean();
    http_response_code(500);
    // Return PHPMailer error message for debugging (safe to show while developing)
    echo json_encode(['success' => false, 'message' => 'Mailer Error: ' . ($mail->ErrorInfo ?: $e->getMessage())]);
    exit;
}
