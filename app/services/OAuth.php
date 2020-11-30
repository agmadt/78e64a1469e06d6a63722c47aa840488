<?php

namespace App\Services;

use OAuth2\Server;
use OAuth2\Storage\Pdo;
use OAuth2\GrantType\AuthorizationCode;
use OAuth2\GrantType\ClientCredentials;

class OAuth
{
    public function init()
    {
        $storage = new Pdo([
            'dsn' => $_ENV['DB_CONNECTION'] . ':host=' . $_ENV['DB_HOST'] . ';dbname=' . $_ENV['DB_NAME'],
            'username' => $_ENV['DB_USERNAME'],
            'password' => $_ENV['DB_PASSWORD']
        ]);

        $server = new Server($storage);
        $server->addGrantType(new AuthorizationCode($storage));
        $server->addGrantType(new ClientCredentials($storage));

        return $server;
    }
}
