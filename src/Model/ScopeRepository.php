<?php

namespace Krak\LavaOAuth2\Model;

use League\OAuth2\Server\{
    Entities\ClientEntityInterface,
    Repositories\ScopeRepositoryInterface
};
use Doctrine\ORM\EntityManagerInterface;

class ScopeRepository implements ScopeRepositoryInterface
{
    private $em;

    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }

    public function getScopeEntityByIdentifier($id) {
        return $this->em->find(Scope::class, $id);
    }

    public function finalizeScopes(
        array $scopes,
        $grant_type,
        ClientEntityInterface $client,
        $user_id = null
    ) {
        $client_scopes = $client->scopes->getValues();
        $scopes = array_intersect($client_scopes, $scopes);
        $scopes = array_filter($scopes, function($scope) use ($grant_type) {
            if ($scope->id == 'user' && $grant_type == 'client_credentials') {
                return false;
            }

            return true;
        });
        return array_values($scopes);
    }
}
