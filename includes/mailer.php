<?php

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

if (!function_exists('configureMailer')) {
    /**
     * Configure PHPMailer with shared SMTP settings.
     */
    function configureMailer(PHPMailer $mail, array $smtpConfig): void
    {
        $mail->isSMTP();
        $mail->Host        = $smtpConfig['host'] ?? 'smtp.gmail.com';
        $mail->SMTPAuth    = true;
        $mail->Username    = $smtpConfig['username'] ?? '';
        $mail->Password    = $smtpConfig['password'] ?? '';
        $mail->SMTPAutoTLS = true;
        $mail->Timeout     = (int)($smtpConfig['timeout'] ?? 20);
        $mail->SMTPOptions = [
            'ssl' => [
                'verify_peer'       => false,
                'verify_peer_name'  => false,
                'allow_self_signed' => true,
            ],
        ];

        $encryption = strtolower($smtpConfig['encryption'] ?? 'tls');
        if ($encryption === 'ssl') {
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        } elseif ($encryption === 'tls') {
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        } else {
            $mail->SMTPSecure = $smtpConfig['encryption'] ?? PHPMailer::ENCRYPTION_STARTTLS;
        }

        $mail->Port    = (int)($smtpConfig['port'] ?? 587);
        $mail->CharSet = 'UTF-8';
        $fromEmail     = $smtpConfig['from_email'] ?? $mail->Username;
        $fromName      = $smtpConfig['from_name'] ?? 'Jyoti Enterprises';
        $mail->setFrom($fromEmail, $fromName);
        $mail->Sender = $fromEmail;
    }
}

if (!function_exists('configureFallbackMailer')) {
    /**
     * Configure PHPMailer to use PHP's native mail() transport as a fallback.
     */
    function configureFallbackMailer(PHPMailer $mail, array $smtpConfig): void
    {
        $mail->isMail();
        $mail->CharSet  = 'UTF-8';
        $fromEmail      = $smtpConfig['from_email']
            ?? $smtpConfig['username']
            ?? ('no-reply@' . ($_SERVER['SERVER_NAME'] ?? 'localhost'));
        $fromName       = $smtpConfig['from_name'] ?? 'Jyoti Enterprises';
        $mail->setFrom($fromEmail, $fromName);
        $mail->Sender = $fromEmail;
    }
}

if (!function_exists('sendMailWithFallback')) {
    /**
     * Attempt to send an email via SMTP and fall back to PHP's mail transport on failure.
     *
     * @param callable $buildMessage A callback that receives a configured PHPMailer instance.
     *
     * @throws Exception When both SMTP and the fallback transport fail.
     */
    function sendMailWithFallback(callable $buildMessage, array $smtpConfig, bool $attemptFallback = true): void
    {
        $transports = ['smtp'];
        if ($attemptFallback) {
            $transports[] = 'mail';
        }

        $lastException = null;

        foreach ($transports as $transport) {
            $mail = new PHPMailer(true);
            try {
                if ($transport === 'smtp') {
                    configureMailer($mail, $smtpConfig);
                } else {
                    configureFallbackMailer($mail, $smtpConfig);
                }

                $buildMessage($mail);
                $mail->send();
                return;
            } catch (Exception $exception) {
                $lastException = $exception;
                error_log(sprintf('Mailer (%s) attempt failed: %s', strtoupper($transport), $exception->getMessage()));
            }
        }

        if ($lastException !== null) {
            throw $lastException;
        }
    }
}
