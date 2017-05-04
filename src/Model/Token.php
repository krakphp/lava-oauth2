<?php

namespace Krak\LavaOAuth2\Model;

trait Token
{
    public $id;
    public $expiry_date_time;
    public $is_revoked;
    public $created_at;

    private function initToken() {
        $this->is_revoked = false;
        $this->created_at = new \DateTime();
    }

    public function getIdentifier() {
        return $this->id;
    }

    public function setIdentifier($id) {
        $this->id = $id;
    }

    public function getExpiryDateTime() {
        return $this->expiry_date_time;
    }

    public function setExpiryDateTime(\DateTime $expiry_date_time) {
        $this->expiry_date_time = $expiry_date_time;
    }
}
