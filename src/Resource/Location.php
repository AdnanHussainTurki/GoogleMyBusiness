<?php

namespace AdnanHussainTurki\Resource;

use AdnanHussainTurki\GoogleMyBusiness;
use AdnanHussainTurki\Resource\Media;
use AdnanHussainTurki\Resource\Post;


/**
 * Location
 */
class Location
{
    public $name;
    public $locationName;
    public $description;
    public $phone;
    public $website;
    public $openStatus;
    public $mapUrl;
    public $reviewUrl;
    public $languageCode;
    public $address;


    protected $canDelete;
    protected $canUpdate;
    protected $hasPendingEdits;
    protected $hasPendingVerification;
    protected $isDisabled;
    protected $isDisconnected;
    protected $isDuplicate;
    protected $isGoogleUpdated;
    protected $isLocalPostApiDisabled;
    protected $isPendingReview;
    protected $isPublished;
    protected $isSuspended;
    protected $isVerified;
    protected $needsReverification;

    protected $addressLines;
    protected $administrativeArea;
    protected $locality;
    protected $organization;
    protected $postalCode;
    protected $revision;
    protected $sortingCode;
    protected $sublocality;
    protected $regionCode;

    protected $client;


    function __construct(\Google_Model $location= null, GoogleMyBusiness $client= null)
    {
        if (!is_null($location)) {
            $this->make($location);
        }
        if (!is_null($client)) {
            $this->provideClient($client);
        }
    }
    public function medias()
    {
        if (!$this->client) {
            throw new ClientNotConfiguredOrProvidedException("Medias for this location cannot be fetched.", 1);
            return false;
        }
        $medias_google_model =  $this->client->getBusiness()->accounts_locations_media->listAccountsLocationsMedia($this->name);
        $medias = [];
        foreach ($medias_google_model as $media) {
            $m = new Media($media, $this->client);
            $m->setLocationId($this->name);
            $medias[] = $m;
        }
        return $medias;
    }
    public function posts()
    {
        if (!$this->client) {
            throw new ClientNotConfiguredOrProvidedException("Posts for this location cannot be fetched.", 1);
            return false;
        }
        $posts_google_model =  $this->client->getBusiness()->accounts_locations_localPosts->listAccountsLocationsLocalPosts($this->name)->getLocalPosts();
        $posts = [];
        foreach ($posts_google_model as $post) {
            $p = new Post($post, $this->client);
            $p->setLocationId($this->name);
            $posts[] = $p;
        }
        return $posts;
    }
    public function make(\Google_Model $location) {
        $this->name = $location->name;
        $this->locationName = $location->locationName;
        $this->description = $location->getProfile()->getDescription();
        $this->phone = $location->primaryPhone;
        $this->website = $location->websiteUrl;
        $this->languageCode = $location->languageCode;
        $this->openStatus = $location->getOpenInfo()->getStatus();
        $this->mapUrl = $location->getMetadata()->getMapsUrl();
        $this->reviewUrl = $location->getMetadata()->getNewReviewUrl();

        $this->canDelete = $location->getLocationState()->canDelete;
        $this->canUpdate = $location->getLocationState()->canUpdate;
        $this->hasPendingEdits = $location->getLocationState()->hasPendingEdits;
        $this->hasPendingVerification = $location->getLocationState()->hasPendingVerification;
        $this->isDisabled = $location->getLocationState()->isDisabled;
        $this->isDisconnected = $location->getLocationState()->isDisconnected;
        $this->isDuplicate = $location->getLocationState()->isDuplicate;
        $this->isGoogleUpdated = $location->getLocationState()->isGoogleUpdated;
        $this->isLocalPostApiDisabled = $location->getLocationState()->isLocalPostApiDisabled;
        $this->isPendingReview = $location->getLocationState()->isPendingReview;
        $this->isPublished = $location->getLocationState()->isPublished;
        $this->isSuspended = $location->getLocationState()->isSuspended;
        $this->isVerified = $location->getLocationState()->isVerified;
        $this->needsReverification = $location->getLocationState()->needsReverification;

        $this->addressLines = $location->getAddress()->getAddressLines();
        $this->administrativeArea = $location->getAddress()->getAdministrativeArea();
        $this->locality = $location->getAddress()->getLocality();
        $this->organization = $location->getAddress()->getOrganization();
        $this->postalCode = $location->getAddress()->getPostalCode();
        $this->revision = $location->getAddress()->getRevision();
        $this->sortingCode = $location->getAddress()->getSortingCode();
        $this->sublocality = $location->getAddress()->getSublocality();
        $this->regionCode = $location->getAddress()->getRegionCode();
        $this->buildAddress();
        return $this;
    }
    function provideClient(GoogleMyBusiness $client)
    {
        $this->client = $client;
        return $this;
    }
    public function getLocationId()
    {
        return $this->name;
    }
    protected function buildAddress()
    {
        $this->address = (($this->addressLines) ? trim(trim(implode(" ", $this->addressLines)), ",") . " ": "")  ;
        $this->address .= ($this->organization) ? $this->organization ." " : "";
        $this->address .= ($this->sublocality) ? $this->sublocality ." " : "";
        $this->address .= ($this->locality) ? $this->locality ." " : "";
        $this->address .= ($this->postalCode) ? $this->postalCode." " : "";
        $this->address .= ($this->regionCode) ? $this->regionCode ." ": "";
        $this->address = trim($this->address);
    }
}