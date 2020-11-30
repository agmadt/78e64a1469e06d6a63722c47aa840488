<?php

use Phroute\Phroute\Exception\HttpRouteNotFoundException;
use Phroute\Phroute\Exception\HttpMethodNotAllowedException;

require __DIR__ . '/api.php';

$dispatcher = new Phroute\Phroute\Dispatcher($router->getData());

header('Content-Type: application/json');

try {
    http_response_code(200);
    $response = $dispatcher->dispatch($_SERVER['REQUEST_METHOD'], parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
    return $response;
} catch (HttpRouteNotFoundException $e) {
    http_response_code(404);
    echo json_encode([
        'message' => 'Route not found'
    ]);
    die();
} catch (HttpMethodNotAllowedException $e) {
    http_response_code(405);
    echo json_encode([
        'message' => 'Method not allowed'
    ]);
    die();
}
