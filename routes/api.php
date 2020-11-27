<?php

use Phroute\Phroute\RouteCollector;

$router = new RouteCollector();

$router->post('/emails/send', ['App\\Controllers\\EmailController', 'send']);
