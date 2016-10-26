<?php

class AccessToken {

    /**
     * @var string
     */
    private $company;

    /**
     * @var string
     */
    private $apiKey;

    /**
     * @var string
     */
    private $secretKey;

    public function __construct($apiKey, $secretKey, $company) {
        $this->apiKey = $apiKey;
        $this->secretKey = $secretKey;
        $this->company = $company;
    }

    /**
     * @return string
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * @return string
     */
    public function getSecretKey()
    {
        return $this->secretKey;
    }

    /**
     * @return string
     */
    public function getCompany()
    {
        return $this->company;
    }


}