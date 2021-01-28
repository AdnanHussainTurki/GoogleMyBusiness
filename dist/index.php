<?php

require_once "../vendor/autoload.php";
require_once "../src/GoogleMyBusiness.php";

use AdnanHussainTurki\GoogleMyBusiness;
use AdnanHussainTurki\Resource\Account;
use AdnanHussainTurki\Resource\Media;
use AdnanHussainTurki\Resource\Post;


$gmb = new GoogleMyBusiness("APP_KEY", "APP_SECRET");
$gmb->setRefreshToken("_REFRESH_TOKEN_");

$account = new Account();
$account->provideClient($gmb);

$accounts = $account->list();
$firstAccount = $accounts[0];

$locations = $accounts[0]->locations();
$firstLocation = $locations[0];

$posts = $firstLocation->posts();
$firstPost = $posts[0];

$medias = $firstLocation->medias();
$firstMedia = $medias[0];

$media = new Media;
$media->provideClient($gmb);
$media->setLocationId($firstLocation->getLocationId());
$media->setCategory("ADDITIONAL");
$media->setFormat('PHOTO');
$media->setSourceUrl("https://cdn.torksky.com/projects/torksky_control/production/products/06019E50K0/ff37c4a25fcf48975831153cc376dfdd.jpeg");
$media->setDescription("Bosch GAS 15");
$media = $media->create();

// $media2 = new Media;
// $media2->provideClient($gmb);
// $media2->setLocationId($firstLocation->getLocationId());
// $media2->setCategory("ADDITIONAL");
// $media2->setFormat('PHOTO');
// $media2->setSourceUrl("https://cdn.torksky.com/projects/torksky_control/production/products/06019E50K0/96f8f2627fc9491c9e672f5ce985f749.jpeg");
// $media2->setDescription("Bosch GAS 15");


$media3 = new Media;
$media3->provideClient($gmb);
$media3->setLocationId($firstLocation->getLocationId());
$media3->setCategory("ADDITIONAL");
$media3->setFormat('PHOTO');
$media3->setSourceUrl("https://cdn.torksky.com/projects/torksky_control/production/products/06019E50K0/a05bb74f618fec4d07b0e1c1693a4ab8.jpeg");
$media3->setDescription("Bosch GAS 15");

$post = new Post;
$post->provideClient($gmb);
$post->setLocationId($firstLocation->getLocationId());
$post->setCallToAction("ORDER", "https://torksky.com/catalogue/70d1f7b121c397fafc00c6127e7d14c2/view");
// $post->setOffer("REPUBLIC", "https://torksky.com", "Only on drill drivers");
$post->setName("Bosch GAS 15");
$post->setDescription("Bosch GAS 15 order now");
$post->setLanguageCode("en");
// $post->addMedia($media);
// $post->addMedia($media2);
$post->addMedia($media3);
$post->create();