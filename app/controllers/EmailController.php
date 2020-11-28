<?php

namespace App\Controllers;

use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use App\DB;

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

        if ($error = $this->validate($body)) {
            header('HTTP/1.1 422 Unprocessable Entity');
            echo json_encode([
                'message' => $error
            ]);
            return;
        }

        $db = new DB();
        foreach ($body->emails as $email) {
            try {
                $mail = new PHPMailer(true);
                $mail->SMTPAuth = true;
                $mail->Host = $_ENV['MAIL_HOST'];
                $mail->Username = $_ENV['MAIL_USERNAME'];
                $mail->Password = 'asd';
                $mail->SMTPSecure = $_ENV['MAIL_ENCRYPTION'];
                $mail->isSMTP();
                $mail->Port = $_ENV['MAIL_PORT'];
                $mail->isHTML(true);
                $mail->setFrom($_ENV['MAIL_FROM'], $_ENV['MAIL_FROM_NAME']);
                $mail->addAddress($email->to);
                $mail->Subject = 'Here is the subject';
                $mail->Body = 'Test message';
                $mail->send();

                $db->query('INSERT INTO sent_emails (payload, sent_at) VALUES (:payload, :sent_at)');
                $db->bind(':payload', json_encode([
                    'to' => $email->to,
                    'subject' => $email->subject,
                    'message' => $email->message
                ]));
                $db->bind(':sent_at', date('Y-m-d H:i:s'));
                $db->execute();
            } catch (Exception $e) {
                $db->query('INSERT INTO unsent_emails (payload, failed_at) VALUES (:payload, :failed_at)');
                $db->bind(':payload', json_encode([
                    'to' => $email->to,
                    'subject' => $email->subject,
                    'message' => $email->message
                ]));
                $db->bind(':failed_at', date('Y-m-d H:i:s'));
                $db->execute();
            }
        }

        echo json_encode([
            'message' => 'Emails sent'
        ]);
    }

    /**
     * Validate function
     *
     * @return mixed
     */
    public function validate($request)
    {
        if (empty($request->emails) || !isset($request->emails)) {
            return 'Emails cannot be empty';
        }

        if (!is_array($request->emails)) {
            return 'Emails must be an array';
        }

        foreach ($request->emails as $key => $email) {
            if (empty($email->to) || !isset($email->to)) {
                return 'Emails ' . $key . ' `to` ' . 'is required';
            }

            if (!filter_var($email->to, FILTER_VALIDATE_EMAIL)) {
                return 'Emails ' . $key . ' `to` ' . 'must be an email';
            }

            if (empty($email->subject) || !isset($email->subject)) {
                return 'Emails ' . $key . ' `subject` ' . 'is required';
            }

            if (empty($email->message) || !isset($email->message)) {
                return 'Emails ' . $key . ' `message` ' . 'is required';
            }
        }

        return null;
    }
}
