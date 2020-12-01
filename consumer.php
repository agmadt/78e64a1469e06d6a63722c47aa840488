<?php

require_once __DIR__ . '/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

use App\Services\Email;
use App\Services\EmailLog;
use PHPMailer\PHPMailer\Exception;
use PhpAmqpLib\Connection\AMQPStreamConnection;

$connection = new AMQPStreamConnection(
    $_ENV['RABBIT_HOST'],
    $_ENV['RABBIT_PORT'],
    $_ENV['RABBIT_USER'],
    $_ENV['RABBIT_PASSWORD'],
    $_ENV['RABBIT_VHOST']
);
$channel = $connection->channel();

$channel->queue_declare('SEND_EMAIL', false, true, false, false);

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
