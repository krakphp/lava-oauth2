<?php

namespace Krak\LavaOAuth2\Model;

use League\OAuth2\Server\Entities\ScopeEntityInterface;

class Scope implements ScopeEntityInterface
{
    public $id;
    public $name;
    public $description;
    public $created_at;

    public function __construct($id, $name, $description = '') {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->created_at = new \DateTime();
    }

    public function getIdentifier() {
        return $this->id;
    }

    public function jsonSerialize() {
        return $this->id;
    }

    public function __toString() {
        return $this->id;
    }
}
