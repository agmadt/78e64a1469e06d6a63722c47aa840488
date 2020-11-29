<?php

namespace App\Services;

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

/**
 * Email class
 */
class Email
{
    /**
     * Send email
     *
     * @param string $payload 
     * 
     * @return void
     */
    public function send(string $payload): void
    {
        $emailPayload = json_decode($payload);

        try {
            $mail = new PHPMailer(true);
            $mail->SMTPAuth = true;
            $mail->Host = $_ENV['MAIL_HOST'];
            $mail->Username = $_ENV['MAIL_USERNAME'];
            $mail->Password = $_ENV['MAIL_PASSWORD'];
            $mail->SMTPSecure = $_ENV['MAIL_ENCRYPTION'];
            $mail->isSMTP();
            $mail->Port = $_ENV['MAIL_PORT'];
            $mail->isHTML(true);
            $mail->setFrom($_ENV['MAIL_FROM'], $_ENV['MAIL_FROM_NAME']);
            $mail->addAddress($emailPayload->to);
            $mail->Subject = $emailPayload->subject;
            $mail->Body = $emailPayload->message;
            $mail->send();
        } catch (Exception $e) {
            throw new Exception('Send email failed');
        }
    }
}
