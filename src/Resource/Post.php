<?php

namespace AdnanHussainTurki\Resource;

use AdnanHussainTurki\GoogleMyBusiness;
use AdnanHussainTurki\Resource\Media;

/**
 * Media
 */
class Post
{
    protected $name;

    protected $description;
    protected $type;
    protected $state;
    protected $languageCode;
    protected $publicUrl;

    protected $callToActionType;
    protected $callToActionUrl;

    protected $couponCode;
    protected $redeemOnlineUrl;
    protected $termsConditions;

    protected $medias = [];

    protected $createdAt;
    protected $updatedAt;

    protected $permissableCallToActionType = [
        "BOOK",
        "ORDER",
        "SHOP",
        "LEARN_MORE",
        "SIGN_UP",
        "CALL"
    ];

    public $locationId;
    protected $client;
    function __construct(\Google_Model $post = null, GoogleMyBusiness $client = null)
    {
        if (!is_null($post)) {
            $this->make($post);
        }
        if (!is_null($client)) {
            $this->provideClient($client);
        }
    }
    public function make(\Google_Model $post)
    {
        $this->name = $post->name;
        $this->description = $post->summary;
        $this->type = $post->topicType;
        $this->state = $post->state;
        $this->languageCode = $post->languageCode;
        $this->publicUrl = $post->searchUrl;


        $this->callToActionType = ((null !== $post->getCallToAction()) ? $post->getCallToAction()->getActionType() : null);
        $this->callToActionUrl = ((null !== $post->getCallToAction()) ? $post->getCallToAction()->getUrl() : null);

        $this->medias = [];
        foreach ($post->getMedia() as $media) {
            $this->medias[] = new Media($media, $this->client);
        }

        $this->createdAt = $post->createTime;
        $this->updatedAt = $post->updateTime;
        return $this;
    }
    public function setLocationId(string $locationId)
    {
        $this->locationId = $locationId;
        return $this;
    }
    public function provideClient(GoogleMyBusiness $client)
    {
        $this->client = $client;
        return $this;
    }
    public function medias()
    {
        return $this->medias;
    }
    public function setCallToAction($type, $url)
    {
        if (!in_array($type, $this->permissableCallToActionType)) {
            throw new \Exception("Invalid call to action type provided", 1);
            return false;
        }
        $this->callToActionType = $type;
        $this->callToActionUrl = $url;
        return $this;
    }
    public function isCallToActionSet()
    {
        if ($this->callToActionType AND $this->callToActionType) {
            return true;
        }
        return false;
    }
    public function setOffer($couponCode, $redeemOnlineUrl, $terms)
    {
        $this->couponCode = $couponCode;
        $this->redeemOnlineUrl = $redeemOnlineUrl;
        $this->termsConditions = $terms;
        return $this;
    }
    public function isOfferSet()
    {
        if ($this->couponCode AND $this->redeemOnlineUrl AND $this->termsConditions) {
            return true;
        }
        return false;
    }
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }
    public function setLanguageCode($languageCode)
    {
        $this->languageCode = $languageCode;
        return $this;
    }
    public function clearMedia()
    {
        $this->medias = [];
        return $this;
    }
    public function addMedia(Media $media)
    {
        $this->medias[] = $media;
        return $this;
    }
    public function setMedia(Media $media)
    {
        $this->clearMedia();
        $this->addMedia($media);
        return $this;
    }
    public function create()
    {
        if (!$this->name) {
            throw new \Exception("Name must be set to create a post.", 1);
            return false;
        }
        if (!$this->description) {
            throw new \Exception("Description must be set to create a post.", 1);
            return false;
        }
        if (!$this->languageCode) {
            throw new \Exception("Language code must be set to create a post.", 1);
            return false;
        }
        if (!$this->locationId) {
            throw new \Exception("Location ID must be set to create a post.", 1);
            return false;
        }
        if (count($this->medias) > 1) {
            throw new \Exception("A post can only have one media attached to it.", 1);
            return false;
        }
        $post_body = new \Google_Service_MyBusiness_LocalPost;
        $post_body->setLanguageCode($this->languageCode);
        $post_body->setSummary($this->description); 
        if ($this->medias) {
            $medias = [];
            foreach($this->medias as $m) {
                $media = new \Google_Service_MyBusiness_MediaItem;
                $media->setMediaFormat($m->format);
                $media->setSourceUrl($m->sourceUrl); 
                $media->setDescription($m->description); 
                $medias[] = $media;
            }
            $post_body->setMedia($medias);
        }
        if ($this->isCallToActionSet()) {
            $call = new \Google_Service_MyBusiness_CallToAction;
            $call->setActionType($this->callToActionType); 
            $call->setUrl($this->callToActionUrl);
            $post_body->setCallToAction($call);

        }
        if ($this->isOfferSet()) {
            $offer = new \Google_Service_MyBusiness_LocalPostOffer;
            $offer->setCouponCode($this->couponCode); 
            $offer->setRedeemOnlineUrl($this->redeemOnlineUrl); 
            $offer->setTermsConditions($this->termsConditions); 
            $post_body->setOffer($offer);
        }

        $post_google_model =  $this->client->getBusiness()->accounts_locations_localPosts->create($this->locationId, $post_body); 
        $this->make($post_google_model);
        return $this;
    }
}