<?php

namespace Krak\LavaOAuth2\Model;

use League\OAuth2\Server\Entities\{
    RefreshTokenEntityInterface,
    AccessTokenEntityInterface
};

class RefreshToken implements RefreshTokenEntityInterface
{
    use Token;

    public $access_token;

    public function __construct() {
        $this->initToken();
    }

    public function getAccessToken() {
        return $this->access_token;
    }

    public function setAccessToken(AccessTokenEntityInterface $access_token) {
        $this->access_token = $access_token;
    }
}
