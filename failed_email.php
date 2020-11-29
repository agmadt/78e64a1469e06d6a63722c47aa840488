<?php

require_once __DIR__ . '/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

use App\DB;
use App\Services\Email;
use App\Services\EmailLog;
use PHPMailer\PHPMailer\Exception;

$db = new DB();
$db->query('SELECT id, payload, failed_at FROM unsent_emails');
$results = $db->resultset();

foreach ($results as $email) {
    try {
        $mail = new Email;
        $mail->send($email['payload']);

        $mailLog = new EmailLog;
        $mailLog->sentEmail($email['payload']);
        $mailLog->deleteUnsentEmail($email['id']);
    } catch (Exception $e) {
        // Log failed sending an unsent emails
    }
}
