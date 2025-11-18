<?php

use PHPMailer\PHPMailer\PHPMailer;

if (!function_exists('configureMailer')) {
    /**
     * Configure PHPMailer with shared SMTP settings.
     */
    function configureMailer(PHPMailer $mail, array $smtpConfig): void
    {
        $mail->isSMTP();
        $mail->Host       = $smtpConfig['host'] ?? 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = $smtpConfig['username'] ?? '';
        $mail->Password   = $smtpConfig['password'] ?? '';

        $encryption = strtolower($smtpConfig['encryption'] ?? 'tls');
        if ($encryption === 'ssl') {
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        } elseif ($encryption === 'tls') {
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        } else {
            $mail->SMTPSecure = $smtpConfig['encryption'] ?? PHPMailer::ENCRYPTION_STARTTLS;
        }

        $mail->Port     = (int)($smtpConfig['port'] ?? 587);
        $mail->CharSet  = 'UTF-8';
        $fromEmail      = $smtpConfig['from_email'] ?? $mail->Username;
        $fromName       = $smtpConfig['from_name'] ?? 'Jyoti Enterprises';
        $mail->setFrom($fromEmail, $fromName);
    }
}
