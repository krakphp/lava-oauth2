<?php

namespace Krak\LavaOAuth2\Model;

use League\OAuth2\Server\Entities\{
    ScopeEntityInterface,
    AccessTokenEntityInterface,
    ClientEntityInterface,
    Traits\AccessTokenTrait
};
use Doctrine\Common\Collections\ArrayCollection;

class AccessToken implements AccessTokenEntityInterface
{
    use AccessTokenTrait;
    use Token;

    public $user_id;
    public $client;
    public $scopes;

    public function __construct() {
        $this->scopes = new ArrayCollection();
        $this->initToken();
    }

    public function setUserIdentifier($user_id) {
        $this->user_id = $user_id;
    }

    public function getUserIdentifier() {
        return $this->user_id;
    }

    public function getClient() {
        return $this->client;
    }

    public function setClient(ClientEntityInterface $client) {
        $this->client = $client;
    }

    public function addScope(ScopeEntityInterface $scope) {
        $this->scopes->add($scope);
    }

    public function getScopes() {
        return $this->scopes->getValues();
    }
}
