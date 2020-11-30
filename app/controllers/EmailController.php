<?php

namespace App\Controllers;

use OAuth2\Request;
use App\Services\OAuth;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Connection\AMQPStreamConnection;

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
    public function send()
    {
        $server = (new OAuth)->init();
        $request = Request::createFromGlobals();
        if (!$server->verifyResourceRequest($request)) {
            return $server->getResponse()->send();
        }

        $body = json_decode(file_get_contents('php://input'));

        $errorMessage = $this->validate($body);
        if (!empty($errorMessage)) {
            header('HTTP/1.1 422 Unprocessable Entity');
            echo json_encode([
                'message' => $errorMessage
            ]);
            return;
        }

        $connection = new AMQPStreamConnection(
            $_ENV['RABBIT_HOST'],
            $_ENV['RABBIT_PORT'],
            $_ENV['RABBIT_USER'],
            $_ENV['RABBIT_PASSWORD'],
            $_ENV['RABBIT_VHOST']
        );
        $channel = $connection->channel();
        $channel->queue_declare('EMAIL', false, true, false, false);

        foreach ($body->emails as $email) {
            $msg = new AMQPMessage(
                json_encode([
                    'to' => $email->to,
                    'subject' => $email->subject,
                    'message' => $email->message
                ]),
                array('delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT)
            );
            $channel->basic_publish($msg, '', 'SEND_EMAIL');
        }

        $channel->close();
        $connection->close();

        echo json_encode([
            'message' => 'Emails sent'
        ]);
    }

    /**
     * Validate function
     * 
     * @param $request 
     *
     * @return string
     */
    public function validate($request): string
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

        return '';
    }
}
