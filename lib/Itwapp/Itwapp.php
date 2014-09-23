<?php

abstract class Itwapp {
    /**
     * @var string The Itwapp API key to be used for requests.
     */
    public static $apiKey;

    /**
     * @var string The Itwapp API secret key to be used for requests.
     */
    public static $secretKey;
    /**
     * @var string The base URL for the Itwapp API.
     */
    public static $apiBase = 'https://itwapp.io';

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
} 