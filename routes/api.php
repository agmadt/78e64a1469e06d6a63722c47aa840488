<?php

use Phroute\Phroute\RouteCollector;

$router = new RouteCollector();

$router->post('/authorize', ['App\\Controllers\\AuthController', 'authorize']);
$router->post('/access_token', ['App\\Controllers\\AuthController', 'accessToken']);

$router->post('/emails/send', ['App\\Controllers\\EmailController', 'send']);
