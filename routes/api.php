<?php

use Phroute\Phroute\RouteCollector;

$router = new RouteCollector();

$router->post('/emails/send', function () {
    echo json_encode([
        'message' => 'Route'
    ]);
});
