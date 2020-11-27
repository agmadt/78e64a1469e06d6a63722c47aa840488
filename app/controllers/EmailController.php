<?php

namespace App\Controllers;

use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

/**
 * EmailController class
 */
class EmailController
{
    /**
     * Send email
     * 
     * @return void
     */
    public function send(): void
    {
        $body = json_decode(file_get_contents('php://input'));

        if (empty($body->emails) || !is_array($body->emails)) {
            header('HTTP/1.1 422 Unprocessable Entity');
            echo json_encode([
                'message' => 'Problem with request'
            ]);
            return;
        }

        $unsentEmail = [];
        $uniqueEmails = array_unique($body->emails);
        foreach ($uniqueEmails as $email) {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                continue;
            }

            try {
                $mail = new PHPMailer(true);
                $mail->SMTPAuth = true;
                $mail->SMTPDebug = SMTP::DEBUG_SERVER;
                $mail->Host = $_ENV['MAIL_HOST'];
                $mail->Username = $_ENV['MAIL_USERNAME'];
                $mail->Password = $_ENV['MAIL_PASSWORD'];
                $mail->SMTPSecure = $_ENV['MAIL_ENCRYPTION'];
                $mail->isSMTP();
                $mail->Port = $_ENV['MAIL_PORT'];
                $mail->isHTML(true);
                $mail->setFrom($_ENV['MAIL_FROM'], $_ENV['MAIL_FROM_NAME']);
                $mail->addAddress($email);
                $mail->Subject = 'Here is the subject';
                $mail->Body    = 'Test message';
                $mail->send();
            } catch (Exception $e) {
                $unsentEmail[] = $email;
            }
        }

        echo json_encode([
            'message' => 'Emails sent'
        ]);
    }
}
