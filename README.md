AlexPSocialAuthBundle
======================

This the bundle help for authentication with a social networks

## Installation

### Step 1: Download AvalancheImagineBundle using composer

Add AlexPSocialAuthBundle in your composer.json:

```js
{
    "require": {
        "alexp/socialauth": "v1.0"
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
# app/config/routing.yml

alex_p_social_auth:
    enabled: ['vk', 'facebook', 'google_plus', 'linkedin', 'odnoklassniki']
```