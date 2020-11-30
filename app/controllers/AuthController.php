<?php

namespace App\Controllers;

use App\DB;
use App\Services\OAuth;
use OAuth2\Server;
use OAuth2\Request;
use OAuth2\Response;
use OAuth2\Storage\Pdo;
use OAuth2\Storage\Memory;
use OAuth2\GrantType\AuthorizationCode;
use OAuth2\GrantType\ClientCredentials;

class AuthController
{
    public function authorize()
    {
        $server = (new OAuth)->init();
        $request = Request::createFromGlobals();
        $request->request['grant_type'] = 'authorization_code';
        $request->request['response_type'] = 'code';
        $request->request['state'] = md5(date('Y-m-d H:i:s'));
        $response = new Response();

        $errorMessage = $this->_authorizeValidation($request);
        if (!empty($errorMessage)) {
            header('HTTP/1.1 422 Unprocessable Entity');
            echo json_encode([
                'message' => $errorMessage
            ]);
            return;
        }

        $client = $this->_isClientExist($request);
        if (!$client) {
            header('HTTP/1.1 401 Unauthorized');
            echo json_encode([
                "message" => "Authorization failed"
            ]);
            return;
        }

        $server->handleAuthorizeRequest($request, $response, true, $client['client_id']);

        $code = substr($response->getHttpHeader('Location'), strpos($response->getHttpHeader('Location'), 'code='));
        echo json_encode([
            "authorization_code" => $code
        ]);
    }

    public function accessToken()
    {
        $server = (new OAuth)->init();
        $request = Request::createFromGlobals();
        $request->request['grant_type'] = 'authorization_code';

        $errorMessage = $this->_accessTokenValidation($request);
        if (!empty($errorMessage)) {
            header('HTTP/1.1 422 Unprocessable Entity');
            echo json_encode([
                'message' => $errorMessage
            ]);
            return;
        }

        $server->handleTokenRequest($request)->send();
    }

    private function _accessTokenValidation(Request $request): string
    {
        $request = $request->request;

        if (empty($request['client_id']) || !isset($request['client_id'])) {
            return "Key client_id is required";
        }

        if (empty($request['client_secret']) || !isset($request['client_secret'])) {
            return "Key client_secret is required";
        }

        if (empty($request['code']) || !isset($request['code'])) {
            return "Key code is required";
        }

        return "";
    }

    private function _authorizeValidation(Request $request): string
    {
        $request = $request->request;

        if (empty($request['client_id']) || !isset($request['client_id'])) {
            return "Key client_id is required";
        }

        if (empty($request['client_secret']) || !isset($request['client_secret'])) {
            return "Key client_secret is required";
        }

        return "";
    }

    private function _isClientExist(Request $request)
    {
        $request = $request->request;

        $db = new DB();
        $db->query('SELECT * FROM oauth_clients WHERE client_id = :client_id AND client_secret = :client_secret');
        $db->bind(':client_id', $request['client_id']);
        $db->bind(':client_secret', $request['client_secret']);
        $db->execute();

        return $db->single();
    }
}
