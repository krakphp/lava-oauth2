<?php

namespace Krak\LavaOAuth2\Model;

use League\OAuth2\Server\Entities\ClientEntityInterface;
use Doctrine\Common\Collections\ArrayCollection;

class Client implements ClientEntityInterface
{
    public $id;
    public $secret;
    public $name;
    public $redirect_uri;
    public $scopes;
    public $created_at;

    public function __construct($id, $secret, $name, $redirect_uri, array $scopes = []) {
        $this->id = $id;
        $this->secret = password_hash($secret, PASSWORD_BCRYPT);
        $this->name = $name;
        $this->redirect_uri = $redirect_uri;
        $this->scopes = new ArrayCollection($scopes);
        $this->created_at = new \DateTime();
    }

    public function getIdentifier() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getRedirectUri() {
        return $this->redirect_uri;
    }

    public function verifySecret($secret) {
        return password_verify($secret, $this->secret);
    }
}
