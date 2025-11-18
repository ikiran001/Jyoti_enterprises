<?php
declare(strict_types=1);

ini_set('display_errors', '1');
error_reporting(E_ALL);

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

require __DIR__ . '/vendor/autoload.php';
$config = require __DIR__ . '/config/app.php';
require_once __DIR__ . '/includes/mailer.php';

function redirectWithStatus(string $status, string $message): void
{
    header('Location: contact.php?status=' . urlencode($status) . '&message=' . urlencode($message), true, 303);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    redirectWithStatus('error', 'Invalid request.');
}

$name    = trim($_POST['name'] ?? '');
$email   = trim($_POST['email'] ?? '');
$mobile  = trim($_POST['mobile'] ?? '');
$message = trim($_POST['message'] ?? '');

if ($name === '' || $email === '' || $mobile === '' || $message === '') {
    redirectWithStatus('error', 'Please fill in all the required fields.');
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    redirectWithStatus('error', 'Please provide a valid email address.');
}

$customerMailFailed = false;

try {
    $adminMail = new PHPMailer(true);
    configureMailer($adminMail, $config['smtp']);
    $adminMail->addAddress($config['admin_email']);
    if (!empty($config['secondary_admin_email']) && $config['secondary_admin_email'] !== $config['admin_email']) {
        $adminMail->addCC($config['secondary_admin_email']);
    }
    $adminMail->addReplyTo($email, $name);
    $adminMail->isHTML(true);
    $adminMail->Subject = "📥 New contact form submission";
    $adminMail->Body = "
        <h3>New Message from Contact Form</h3>
        <p><strong>Name:</strong> " . htmlspecialchars($name) . "</p>
        <p><strong>Email:</strong> " . htmlspecialchars($email) . "</p>
        <p><strong>Mobile:</strong> " . htmlspecialchars($mobile) . "</p>
        <p><strong>Message:</strong><br>" . nl2br(htmlspecialchars($message)) . "</p>
    ";
    $adminMail->send();
} catch (Exception $e) {
    error_log('Contact form admin mail failed: ' . $e->getMessage());
    redirectWithStatus('error', 'We could not deliver your message right now. Please WhatsApp/call +91 ' . $config['admin_phone'] . '.');
}

try {
    $customerMail = new PHPMailer(true);
    configureMailer($customerMail, $config['smtp']);
    $customerMail->addAddress($email, $name ?: 'Customer');
    $customerMail->isHTML(true);
    $customerMail->Subject = "Thank you for contacting {$config['company_name']}!";
    $customerMail->Body = "
        Hi <strong>" . htmlspecialchars($name) . "</strong>,<br><br>
        Thank you for reaching out. We’ve received your message and will respond shortly.<br><br>
        <strong>Your Message:</strong><br>" . nl2br(htmlspecialchars($message)) . "<br><br>
        If you need urgent help, call or WhatsApp us at 📞 <strong>+91 {$config['admin_phone']}</strong>.<br><br>
        Regards,<br>
        <strong>{$config['company_name']}</strong><br>
        📍 {$config['office_location']}
    ";
    $customerMail->send();
} catch (Exception $e) {
    $customerMailFailed = true;
    error_log('Contact form customer mail failed: ' . $e->getMessage());
}

$successMessage = 'Thank you! Your message has been delivered.';
if ($customerMailFailed) {
    $successMessage .= ' We could not send a confirmation email to you, but the team has been notified.';
}

redirectWithStatus('success', $successMessage);