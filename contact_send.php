<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Ensure PHPMailer is installed via Composer

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name    = $_POST['name'] ?? 'Anonymous';
    $email   = $_POST['email'] ?? '';
    $mobile  = $_POST['mobile'] ?? '';
    $message = $_POST['message'] ?? '';

    // === ADMIN EMAIL ===
    $adminEmail = 'kiran.jadhav1993@gmail.com';
    $adminSubject = "📥 New Contact Form Submission";
    $adminBody = "
        <h3>New Message from Contact Form</h3>
        <p><strong>Name:</strong> $name</p>
        <p><strong>Email:</strong> $email</p>
        <p><strong>Mobile:</strong> $mobile</p>
        <p><strong>Message:</strong><br>" . nl2br(htmlspecialchars($message)) . "</p>
    ";

    // === CUSTOMER EMAIL ===
    $customerSubject = "Thank you for contacting Jyoti Enterprises!";
    $customerBody = "
        Hi <strong>$name</strong>,<br><br>
        Thank you for reaching out to us. We’ve received your message:<br><br>
        <strong>Message:</strong><br>" . nl2br(htmlspecialchars($message)) . "<br><br>
        We will get back to you as soon as possible.<br><br>
        Regards,<br>
        <strong>Jyoti Enterprises</strong><br>
        📞 9820730645<br>
        📍 Subhashnagar, Ghatkopar West, Mumbai - 400084
    ";

    try {
        // === Setup mailer
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'kiran.jadhav1993@gmail.com'; // your Gmail
        $mail->Password   = 'chsqmasvvlrvgdus'; // app password
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        // === Send to Admin
        $mail->setFrom('kiran.jadhav1993@gmail.com', 'Jyoti Enterprises');
        $mail->addAddress($adminEmail);
        $mail->isHTML(true);
        $mail->Subject = $adminSubject;
        $mail->Body    = $adminBody;
        $mail->send();

        // === Send to Customer
        $mail->clearAddresses();
        $mail->addAddress($email);
        $mail->Subject = $customerSubject;
        $mail->Body    = $customerBody;
        $mail->send();

        echo "<script>alert('Thank you! Your message has been sent.'); window.location.href='index.html';</script>";
    } catch (Exception $e) {
        echo "<script>alert('Mailer Error: {$mail->ErrorInfo}'); window.location.href='index.html';</script>";
    }
} else {
    echo "<script>alert('Invalid request.'); window.location.href='index.html';</script>";
}
?>
