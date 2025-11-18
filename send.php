<?php
declare(strict_types=1);

ini_set('display_errors', '1');
error_reporting(E_ALL);

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

require __DIR__ . '/vendor/autoload.php';

$config = require __DIR__ . '/config/app.php';
require_once __DIR__ . '/includes/mailer.php';

header('Content-Type: application/json');

/**
 * Send a JSON response and terminate the script.
 */
function respond(bool $success, string $message, array $payload = [], ?int $statusCode = null): void
{
    http_response_code($statusCode ?? ($success ? 200 : 400));
    echo json_encode(array_merge(['success' => $success, 'message' => $message], $payload));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    respond(false, 'Invalid request method.', [], 405);
}

$product = trim($_POST['product'] ?? 'General enquiry');
$name    = trim($_POST['name'] ?? '');
$email   = trim($_POST['email'] ?? '');
$contact = trim($_POST['contact'] ?? ($_POST['phone'] ?? ''));
$message = trim($_POST['message'] ?? '');

if ($product === '') {
    $product = 'General enquiry';
}

if ($name === '' || $email === '' || $contact === '' || $message === '') {
    respond(false, 'Please fill in your name, email, phone number and message.');
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    respond(false, 'Please provide a valid email address.');
}

// Persist enquiry (best-effort)
try {
    $conn = new mysqli("localhost", "jyotiffj_KiranJ", "K@9833514014j", "jyotiffj_jyoti_Enterprises");
    $conn->set_charset('utf8mb4');
    $stmt = $conn->prepare("INSERT INTO enquiries (product, name, contact, email) VALUES (?, ?, ?, ?)");
    if ($stmt) {
        $stmt->bind_param("ssss", $product, $name, $contact, $email);
        $stmt->execute();
        $stmt->close();
    }
    $conn->close();
} catch (Throwable $e) {
    error_log('Enquiry DB error: ' . $e->getMessage());
    // Continue even if DB write fails.
}

$customerMailError = null;
try {
    $mail = new PHPMailer(true);
    configureMailer($mail, $config['smtp']);
    $mail->addAddress($email, $name ?: 'Customer');
    $mail->addReplyTo($config['admin_email'], $config['company_name']);
    $mail->isHTML(true);
    $mail->Subject = "Thank you for your enquiry at {$config['company_name']}!";
    $mail->Body = "
        Hi <strong>" . htmlspecialchars($name) . "</strong>,<br><br>
        Thank you for your interest in <strong>" . htmlspecialchars($product) . "</strong>.<br><br>
        We’ve received your enquiry and our team will reach out soon.<br><br>
        <strong>Your Message:</strong><br>" . nl2br(htmlspecialchars($message)) . "<br><br>
        If urgent, please call or WhatsApp us at 📞 <strong>+91 {$config['admin_phone']}</strong><br><br>
        — Team {$config['company_name']}
    ";
    $mail->send();
} catch (Exception $e) {
    $customerMailError = $e->getMessage();
    error_log('Customer acknowledgement mail failed: ' . $e->getMessage());
}

try {
    $adminMail = new PHPMailer(true);
    configureMailer($adminMail, $config['smtp']);
    $adminMail->addAddress($config['admin_email']);
    if (!empty($config['secondary_admin_email']) && $config['secondary_admin_email'] !== $config['admin_email']) {
        $adminMail->addCC($config['secondary_admin_email']);
    }
    $adminMail->addReplyTo($email, $name);
    $adminMail->isHTML(true);
    $adminMail->Subject = "📩 New enquiry for " . htmlspecialchars($product);
    $adminMail->Body = "
        <h3>New Enquiry Details</h3>
        <p><strong>Product:</strong> " . htmlspecialchars($product) . "</p>
        <p><strong>Name:</strong> " . htmlspecialchars($name) . "</p>
        <p><strong>Contact:</strong> " . htmlspecialchars($contact) . "</p>
        <p><strong>Email:</strong> " . htmlspecialchars($email) . "</p>
        <p><strong>Message:</strong><br>" . nl2br(htmlspecialchars($message)) . "</p>
    ";
    $adminMail->send();
} catch (Exception $e) {
    error_log('Admin notification mail failed: ' . $e->getMessage());
    respond(
        false,
        'We could not notify Jyoti Enterprises right now. Please call or WhatsApp us at +91 ' . $config['admin_phone'] . '.'
    );
}

$successMessage = 'Thank you! Your enquiry has been delivered to Jyoti Enterprises.';
if ($customerMailError) {
    $successMessage .= ' However, we could not send a confirmation email to you.';
}

respond(true, $successMessage);