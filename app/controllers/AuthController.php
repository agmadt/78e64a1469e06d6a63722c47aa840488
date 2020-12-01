<?php

namespace App\Controllers;

use App\DB;
use App\Services\OAuth;
use OAuth2\Request;
use OAuth2\Response;

class AuthController
{
    /**
     * Used for OAuth2 authorize endpoint
     *
     * @return void
     */
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
        parse_str($code, $parsedString);

        echo json_encode([
            "authorization_code" => $parsedString['code'],
            "state" => $parsedString['state']
        ]);
    }

    /**
     * Used for OAuth2 access_token endpoint
     *
     * @return void
     */
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

    /**
     * Private validation for checking parameters for access_token
     *
     * @param Request $request 
     * 
     * @return string
     */
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

    /**
     * Private validation for checking parameters for authorize
     *
     * @param Request $request 
     * 
     * @return string
     */
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

    /**
     * To check whether client exist
     *
     * @param Request $request 
     * 
     * @return void
     */
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
