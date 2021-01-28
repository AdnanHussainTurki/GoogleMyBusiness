<?php

namespace AdnanHussainTurki\Resource;

use AdnanHussainTurki\GoogleMyBusiness;

/**
 * Media
 */
class Media
{
    public $name;
    protected $category;
    public $description;
    public $createdAt;
    public $publicUrl;
    public $format;
    public $sourceUrl;
    public $thumbnailUrl;
    public $viewCount;
    public $heightPixels;
    public $widthPixels;
    public $associatedPriceListItemId;
    public $mediaTypesPermissible = ["PHOTO", "VIDEO"];
    public $categoryPermissible = ["ADDITIONAL", "COVER"];
    public $locationId;
    protected $client;
    function __construct(\Google_Model $media = null, GoogleMyBusiness $client = null)
    {
        if (!is_null($media)) {
            $this->make($media);
        }
        if (!is_null($client)) {
            $this->provideClient($client);
        }
    }
    public function provideClient(GoogleMyBusiness $client)
    {
        $this->client = $client;
        return $this;
    }
    public function make(\Google_Model $media)
    {
        $this->name = $media->name;
        $this->description = $media->description;
        $this->createdAt = $media->createTime;
        $this->publicUrl = $media->googleUrl;
        $this->format = $media->mediaFormat;
        $this->sourceUrl = $media->sourceUrl;
        $this->thumbnailUrl = $media->thumbnailUrl;
        $this->viewCount =  ((null !== $media->getInsights()) ? $media->getInsights()->getViewCount() : null);
        $this->category = ((null !== $media->getLocationAssociation()) ? $media->getLocationAssociation()->getCategory() : null);
        $this->associatedPriceListItemId = ((null !== $media->getLocationAssociation()) ? $media->getLocationAssociation()->getPriceListItemId() : null); 
        $this->heightPixels = ((null !== $media->getDimensions()) ? $media->getDimensions()->getHeightPixels() : null);  
        $this->widthPixels = ((null !== $media->getDimensions()) ? $media->getDimensions()->getWidthPixels() : null); 
        return $this;
    }
    public function setLocationId(string $locationId)
    {
        $this->locationId = $locationId;
        return $this;
    }
    public function setCategory(string $category)
    {
        if (!in_array($category, $this->categoryPermissible)) {
            throw new \Exception("Invalid category provided.", 1);
            return false;
        }
        $this->category = $category;
        return $this;
    }
    public function setFormat(string $format)
    {
        $this->format = $format;
        return $this;
    }
    public function setSourceUrl(string $sourceUrl)
    {
        $this->sourceUrl = $sourceUrl;
        return $this;
    }
    public function setDescription(string $description)
    {
        $this->description = $description;
        return $this;
    }
    public function create($format = null, $sourceUrl = null, $category = null, $description = null)
    {
        if (is_null($this->locationId)) {
            throw new \Exception("Location is not set for which the media/post has to be created.", 1);
            
        }
        if (is_null($format) and is_null($this->format)) {
            throw new \Exception("Format not set for the media", 1);
            
        }
        if (is_null($sourceUrl) and is_null($this->sourceUrl)) {
            throw new \Exception("Source Url not set for the media", 1);
            
        }
        if (is_null($category) and is_null($this->category)) {
            throw new \Exception("Category not set for the media", 1);
            
        }
        if (!$format) {
            $format = $this->format;
        }
        if (!$sourceUrl) {
            $sourceUrl = $this->sourceUrl;
        }
        if (!$description) {
            $description = $this->description;
        }
        if (!$category) {
            $category = $this->category;
        }

        if (!in_array($format, $this->mediaTypesPermissible)) {
            throw new \Exception("Invalid format type provided.", 1);
            
            return false;
        }
        $media = new \Google_Service_MyBusiness_MediaItem;
        $media->setMediaFormat($format);
        $media->setSourceUrl($sourceUrl);
        $media->setThumbnailUrl($sourceUrl);
        $association = new \Google_Service_MyBusiness_LocationAssociation;
        $association->setCategory($category);
        $media->setLocationAssociation($association);
        if ($description) {
            $media->setName($description);
            $media->setDescription($description);
        }
        $mediaResource = $this->client->getBusiness()->accounts_locations_media;
        $mediaGoogleModel = $mediaResource->create($this->locationId,$media);
        $this->make($mediaGoogleModel);
        return $this;
    }
}