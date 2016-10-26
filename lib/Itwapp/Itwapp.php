<?php

abstract class Itwapp {
    /**
     * @var string The Itwapp API key to be used for requests.
     */
    public static $apiKey = null;

    /**
     * @var string The Itwapp API secret key to be used for requests.
     */
    public static $secretKey = null;
    /**
     * @var string The base URL for the Itwapp API.
     */
    public static $apiBase = 'http://itwapp.io';

    /**
     * @return string The API key used for requests.
     */
    public static function getApiKey()
    {
        return self::$apiKey;
    }

    /**
     * Sets the API key to be used for requests.
     *
     * @param string $apiKey
     */
    public static function setApiKey($apiKey)
    {
        self::$apiKey = $apiKey;
    }

    /**
     * @return string The API secret key used for requests.
     */
    public static function getApiSecretKey()
    {
        return self::$secretKey;
    }

    /**
     * Sets the API secret key to be used for requests.
     *
     * @param string $secretKey
     */
    public static function setApiSecretKey($secretKey)
    {
        self::$secretKey = $secretKey;
    }

    /**
     * Init api key and secret key with mail and password
     * @param $mail
     * @param $password
     * @return AccessToken[]
     * @throws InvalidRequestError
     * @throws ResourceNotFoundException
     * @throws ServiceException
     * @throws UnauthorizedException
     */
    public static function Authenticate($mail, $password)
    {
        $body = [
            "mail" => $mail,
            "password" => $password
        ];
        $client = new GuzzleHttp\Client();
        $res = $client->post(Itwapp::$apiBase.'/api/v1/auth/', [
            'json' => $body,
            'exceptions' => false
        ]);
        if($res->getStatusCode() == 200)    {
            $json = $res->json();
            $all = array();
            foreach($json as $auth)    {
                $all[] = new AccessToken($auth["apiKey"], $auth["secretKey"], $auth["company"]);
            }
            return $all;
        }else{
            switch($res->getStatusCode())   {
                case 401 :
                    throw new UnauthorizedException();
                    break;
                case 400 :
                    throw new InvalidRequestError();
                    break;
                case 404 :
                    throw new ResourceNotFoundException();
                    break;
                case 503 :
                case 500 :
                default :
                    throw new ServiceException();
            }
        }
    }
} 