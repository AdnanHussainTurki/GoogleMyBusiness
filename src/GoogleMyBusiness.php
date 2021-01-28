<?php

namespace AdnanHussainTurki;

use AdnanHussainTurki\Resource\Account;


/**
 * GoogleMyBusiness
 * v0.1
 */
class GoogleMyBusiness 
{
    protected $client;
    protected $business;
    function __construct(string $google_client_id, string $google_client_secret)
    {
        $this->client = new \Google_Client;
        $this->client->setClientId($google_client_id);
        $this->client->setClientSecret($google_client_secret);
        $this->client->setScopes([
           "https://www.googleapis.com/auth/business.manage"
        ]);
        $this->client->setApiFormatV2(2);
        $this->business = new \Google_Service_MyBusiness($this->client);
    }
    public function setRefreshToken(string $refreshToken)
    {
        return $this->client->refreshToken($refreshToken);
    }
    public function getClient()
    {
        return $this->client;
    }
    public function getBusiness()
    {
        return $this->business;
    }
}