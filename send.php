<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect form data
    $product = $_POST['product'] ?? 'N/A';
    $name    = $_POST['name'] ?? 'Customer';
    $contact = $_POST['contact'] ?? '';
    $email   = $_POST['email'] ?? '';
    $message = $_POST['message'] ?? '';

    // Save enquiry to database
    $conn = new mysqli("localhost", "jyotiffj_KiranJ", "K@9833514014j", "jyotiffj_jyoti_Enterprises");
    if ($conn->connect_error) {
        die("DB connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("INSERT INTO enquiries (product, name, contact, email) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $product, $name, $contact, $email);
    $stmt->execute();
    $stmt->close();

    // Send email to customer
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'kiran.jadhav1993@gmail.com'; // your Gmail
        $mail->Password   = 'chsqmasvvlrvgdus'; // app password
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        $mail->setFrom('kiran.jadhav1993@gmail.com', 'Jyoti Enterprises');
        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->Subject = "Thank you for your enquiry at Jyoti Enterprises!";
        $mail->Body    = "
            Hi <strong>$name</strong>,<br><br>
            Thank you for your interest in our product: <strong>$product</strong>.<br><br>
            We’ve received your enquiry and our team will contact you soon.<br><br>
            <strong>Your Message:</strong><br>" . nl2br(htmlspecialchars($message)) . "<br><br>
            If urgent, you can also call us at 📞 <strong>9820730645</strong><br><br>
            — Team Jyoti Enterprises
        ";
        $mail->send();
    } catch (Exception $e) {
        // Continue even if customer mail fails
    }

    // Send internal notification to admin
    try {
        $adminMail = new PHPMailer(true);
        $adminMail->isSMTP();
        $adminMail->Host       = 'smtp.gmail.com';
        $adminMail->SMTPAuth   = true;
        $adminMail->Username   = 'kiran.jadhav1993@gmail.com';
        $adminMail->Password   = 'chsqmasvvlrvgdus';
        $adminMail->SMTPSecure = 'tls';
        $adminMail->Port       = 587;

        $adminMail->setFrom('kiran.jadhav1993@gmail.com', 'Jyoti Enterprises');
        $adminMail->addAddress('kiran.jadhav1993@gmail.com');
        $adminMail->isHTML(true);
        $adminMail->Subject = "📩 New Enquiry Received for $product";
        $adminMail->Body = "
            <h3>New Enquiry Details</h3>
            <p><strong>Product:</strong> $product</p>
            <p><strong>Name:</strong> $name</p>
            <p><strong>Contact:</strong> $contact</p>
            <p><strong>Email:</strong> $email</p>
            <p><strong>Message:</strong><br>" . nl2br(htmlspecialchars($message)) . "</p>
        ";
        $adminMail->send();
    } catch (Exception $e) {
        // Ignore if admin mail fails
    }

    echo "<script>alert('Thank you! Your enquiry has been submitted.'); window.location.href='index.html';</script>";
} else {
    echo "<script>alert('Invalid request.'); window.location.href='index.html';</script>";
}
?>
