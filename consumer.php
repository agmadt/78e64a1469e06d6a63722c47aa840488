<?php

require_once __DIR__ . '/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

use App\Services\Email;
use App\Services\EmailLog;
use PHPMailer\PHPMailer\Exception;
use PhpAmqpLib\Connection\AMQPStreamConnection;

$connection = new AMQPStreamConnection('moose.rmq.cloudamqp.com', 5672, 'rrdggrsh', 'oOlXObCkwf82z4koKQXKVdCjvOuwCwB4', 'rrdggrsh');
$channel = $connection->channel();

$channel->queue_declare('EMAIL', false, true, false, false);

echo " [*] Waiting for messages. To exit press CTRL+C\n";

$callback = function ($msg) {
    try {
        $email = new Email;
        $email->send($msg->body);

        $emailLog = new EmailLog;
        $emailLog->sentEmail($msg->body);
    } catch (Exception $e) {
        $emailLog = new EmailLog;
        $emailLog->unsentEmail($msg->body);
    }
};

$channel->basic_consume('SEND_EMAIL', '', false, false, false, false, $callback);

while ($channel->is_consuming()) {
    $channel->wait();
}

$channel->close();
$connection->close();
