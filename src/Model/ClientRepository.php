<?php

namespace Krak\LavaOAuth2\Model;

use League\OAuth2\Server\{
    Repositories\ClientRepositoryInterface
};
use Doctrine\ORM\EntityManagerInterface;

class ClientRepository implements ClientRepositoryInterface
{
    private $em;

    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }

    public function getClientEntity($client_id, $grant_type, $client_secret = null, $must_validate_secret = true) {
        $client = $this->em->find(Client::class, $client_id);
        if (!$client) {
            return;
        }

        if (!$client->redirect_uri && ($grant_type == 'implicit' || $grant_type == 'authorization_code')) {
            return;
        }

        if ($must_validate_secret) {
            $res = $client->verifySecret($client_secret);
            if (!$res) {
                return;
            }
        }

        return $client;
    }
}
