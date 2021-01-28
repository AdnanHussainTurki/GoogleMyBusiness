<?php

namespace AdnanHussainTurki\Resource;

use AdnanHussainTurki\Exceptions\ClientNotConfiguredOrProvidedException;
use AdnanHussainTurki\GoogleMyBusiness;
use AdnanHussainTurki\Resource\Location;


/**
 * Google Account 
 */
class Account
{
    public $name;
    public $account_name;
    public $profileUrl;
    protected $number;
    protected $permissionLevel;
    protected $role;
    protected $vetted;
    protected $status;
    protected $type;
    private $client;

    public function __construct(\Google_Model $account = null, GoogleMyBusiness $client = null)
    {
        if (!is_null($account)) {
            $this->make($account);
        }
        if (!is_null($client)) {
            $this->provideClient($client);
        }
    }
    function provideClient(GoogleMyBusiness $client)
    {
        $this->client = $client;
    }
    public function list()
    {
        if (!$this->client) {
            throw new ClientNotConfiguredOrProvidedException("Accounts cannot be fetched.", 1);
            return false;
        }
        $accounts_google_model = $this->client->getBusiness()->accounts->listAccounts()->getAccounts();
        $accounts = [];
        foreach ($accounts_google_model as $account) {
            $accounts[] = new Account($account,$this->client);
        }
        return $accounts;
    }
    public function make(\Google_Model $account)
    {
        $this->name = $account->getName();
        $this->account_name = $account->getAccountName();
        $this->number = $account->getAccountNumber();
        $this->permissionLevel = $account->getPermissionLevel();
        $this->profileUrl = $account->getProfilePhotoUrl();
        $this->role = $account->getRole();
        $this->vetted = $account->getState()->getVettedStatus();
        $this->status = $account->getState()->getStatus();
        $this->type = $account->getType();
    }
    public function locations()
    {
        if (!$this->client) {
            throw new ClientNotConfiguredOrProvidedException("Location cannot be fetched.", 1);
            return false;
        }
        $locations_google_model = $this->client->getBusiness()->accounts_locations->listAccountsLocations($this->name)->getLocations();
        $locations = [];
        foreach ($locations_google_model as $location) {
            $loc = new Location($location);
            $loc->provideClient($this->client);
            $locations[] = $loc;
        }
        return $locations;
    }
}