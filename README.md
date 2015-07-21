AlexPSocialAuthBundle
======================

This the bundle help for authentication with a social networks

## Installation

### Step 1: Download AvalancheImagineBundle using composer

Add AlexPSocialAuthBundle in your composer.json:

```js
{
    "require": {
        "alexp/socialauth": "v1.0@dev"
    }
}
```

### Step 2: Enable the bundle

Enable the bundle in the kernel:

``` php
<?php
// app/AppKernel.php

public function registerBundles() {
    $bundles = array(
        // ...
        new AlexP\SocialAuthBundle\AlexPSocialAuthBundle(),
    );
}
```

### Step3: Enable needed social networks

``` yaml
# app/config/config.yml

alex_p_social_auth:
    enabled: ['vk', 'facebook', 'google_plus', 'linkedin', 'odnoklassniki']
```

### Step4: Add you data for API access

``` yaml
# app/config/config.yml

alex_p_social_auth:
    enabled: ['vk']
    vk:
        client_id:  YOUR_CLIENT_ID
        secret_key: YOUR_SECRET_KEY
        return_url: YOUR_REDIRECT_URL
```