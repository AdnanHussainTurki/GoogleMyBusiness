# GoogleMyBusiness API PHP Wrapper
Google privately provides its GoogleMyBusiness API to the developers. Being private, the SDK for this API is not available in the [Google Services PHP SDK](https://github.com/googleapis/google-api-php-client "Google Services PHP SDK").  This projects provides a goto and easy to implement way to use GoogleMyBusiness API.

### Requesting Access
Being private API, you need to request access to the same API via the following [form](https://developers.google.com/my-business/content/prereqs "form") for your Google project. You may need to provide your project id and use case.

### Credentials
Once your API request got approved, you need to generate credentials i.e client_id and client_secret. With that, you also need to generate refresh token for the following scope

`https://www.googleapis.com/auth/business.manage`

If you dont know much about how to refresh token, follow the instruction below:
- While generating credentials in Google Developer Console for your API, enter `https://google.myphpnotes.com` in authorised javascript field and `https://google.myphpnotes.com/callback.php` in the redirect_uri filed.
- Then, go to `https://google.myphpnotes.com`
- Provide your client id and client secret in the given field and add scope as following `https://www.googleapis.com/auth/business.manage
- On submission, you will be requested to authorize access to your Google account. Select the respective account and allow the given scope.
- On redirection, you will get several important information like access_token and refresh token.
- Note down the refresh token from that.

### Installation
You can install this library to your project via composer using the following command:

`composer require adnanhussainturki/google-my-business-php`

### List Accounts
The GMB works something like this. Your Google Account have many accounts (usually one) and each account contains multiple locations.
`

    $gmb = new GoogleMyBusiness("APP_KEY", "APP_SECRET");
    $gmb->setRefreshToken("_REFRESH_TOKEN_");
    
    $account = new Account();
    $account->provideClient($gmb);
    
    $accounts = $account->list();
    $firstAccount = $accounts[0];`
    
###     List Locations


    $gmb = new GoogleMyBusiness("APP_KEY", "APP_SECRET");
    $gmb->setRefreshToken("_REFRESH_TOKEN_");
    
    $account = new Account();
    $account->provideClient($gmb);
    
    $accounts = $account->list();
    $firstAccount = $accounts[0];
    
    $locations = $accounts[0]->locations();
    $firstLocation = $locations[0];
    
###     List Location Posts 


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
###     List Location Medias


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
    
    
### Create Media


    $media = new Media;
    $media->provideClient($gmb); // GoogleMyBusiness Client for making credentials available to the media class.
    $media->setLocationId($firstLocation->getLocationId()); // Location ID
    $media->setCategory("ADDITIONAL");
    $media->setFormat('PHOTO');
    $media->setSourceUrl("https://cdn.torksky.com/projects/torksky_control/production/products/06019E50K0/ff37c4a25fcf48975831153cc376dfdd.jpeg");
    $media->setDescription("Bosch GAS 15");
    $media = $media->create();
    
###     Create Post


    $post = new Post;
    $post->provideClient($gmb);
    $post->setLocationId($firstLocation->getLocationId());
    $post->setCallToAction("ORDER", "https://torksky.com/catalogue/70d1f7b121c397fafc00c6127e7d14c2/view");
    $post->setName("Bosch GAS 15");
    $post->setDescription("Bosch GAS 15 order now");
    $post->setLanguageCode("en");
    $post->addMedia($media);
    $post->create();
    
###     Limitations
###### By API
- You cannot add products
- You cannot add offer in posts
- You cannot add more than one media in the post
- There may other limitations also. You may let us know about it.

###### By Library
This library only concerns for following functions (as of now)
- List Accounts
- List Locations
- List Posts of each location
- List Medias of each location
- Create media for a location
- Create post for a location
- Feel free to contribute/raise issue.

### Buy me a coffee
[![](https://img.buymeacoffee.com/api/?url=aHR0cHM6Ly9pbWcuYnV5bWVhY29mZmVlLmNvbS9hcGkvP25hbWU9YWRuYW50dXJraSZzaXplPTMwMCZiZy1pbWFnZT1ibWMmYmFja2dyb3VuZD1mZjgxM2Y=&creator=adnanturki&is_creating=building%20cool%20things%20every%20single%20f**king%20day.&design_code=1&design_color=%23ff813f&slug=adnanturki)](https://www.buymeacoffee.com/adnanturki)

### How to contribute
- Create a fork, make changes and send a pull request.
- Raise a issue

### License
Licensed under Apache 2.0. You can check its details [here](https://choosealicense.com/licenses/apache-2.0/ "here").
