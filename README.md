# Lava OAuth2

This library provides OAuth2 integration with the Krak\\Lava framework. It uses the `league/oauth2-server` package for all of the heavy lifting and the Doctrine ORM for the backend.

## Installation

Install with composer at `krak/lava-oauth2`

## Usage

```php
<?php

use Krak\LavaOAuth2;

$app->with(new LavaOAuth2\OAuth2Package());
$app['krak.lava_oauth2']['private_key'] = $app->basePath('oauth-private.key');
$app['krak.lava_oauth2']['public_key'] = $app->basePath('oauth-public.key');
$app['krak.lava_oauth2']['grants'] = [
    'client_credentials' => true,
    'access_token_ttl' => new \DateInterval('PT2H'),
];

$app->httpStack()->push($app['krak.lava_oauth2']['resource_server_middleware']);
$app->httpStack()->push(mount('/oauth', $app['krak.lava_oauth2']));
```

This configures the lava_oauth2 application and adds the oauth2 server and middleware onto the main application.
