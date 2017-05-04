<?php

namespace Krak\LavaOAuth2;

use Krak\Arr;
use Krak\Lava;
use Krak\Http;
use League\OAuth2\Server\{
    AuthorizationServer,
    Exception\OAuthServerException,
    Grant\ClientCredentialsGrant,
    Grant\PasswordGrant,
    Grant\RefreshTokenGrant,
    Middleware\ResourceServerMiddleware,
    Repositories\AccessTokenRepositoryInterface,
    Repositories\ClientRepositoryInterface,
    Repositories\RefreshTokenRepositoryInterface,
    Repositories\ScopeRepositoryInterface,
    Repositories\UserRepositoryInterface,
    ResourceServer
};

class OAuth2InternalPackage implements Lava\Package
{
    public function with(Lava\App $app) {
        $app->with(new Lava\Package\ExceptionHandlerPackage());
        $app->with(new Lava\Package\RESTPackage());
        $app->with(new DoctrinePackage());

        $app->singleton(AccessTokenRepositoryInterface::class, Model\TokenRepository::class);
        $app->singleton(ClientRepositoryInterface::class, Model\ClientRepository::class);
        $app->singleton(RefreshTokenRepositoryInterface::class, Model\TokenRepository::class);
        $app->singleton(ScopeRepositoryInterface::class, Model\ScopeRepository::class);
        $app->singleton(Model\Seed::class);
        $app->alias(Model\Seed::class, 'seed');
        $app[AuthorizationServer::class] = function($app) {
            $server = new AuthorizationServer(
                $app[ClientRepositoryInterface::class],
                $app[AccessTokenRepositoryInterface::class],
                $app[ScopeRepositoryInterface::class],
                $app['private_key'],
                $app['public_key']
            );
            $grants = $app['grants'];
            if (Arr\get($grants, 'refresh_token', false)) {
                $grant = new RefreshTokenGrant($app[RefreshTokenRepositoryInterface::class]);
                if (isset($grants['refresh_token_ttl'])) {
                    $grant->setRefreshTokenTTL($grants['refresh_token_ttl']);
                }
                $server->enableGrantType($grant, $grants['access_token_ttl']);
            }
            if (Arr\get($grants, 'client_credentials', false)) {
                $server->enableGrantType(new ClientCredentialsGrant(), $grants['access_token_ttl']);
            }
            if (Arr\get($grants, 'password', false)) {
                $grant = new PasswordGrant(
                    $app[UserRepositoryInterface::class],
                    $app[RefreshTokenRepositoryInterface::class]
                );
                if (isset($grants['refresh_token_ttl'])) {
                    $grant->setRefreshTokenTTL($grants['refresh_token_ttl']);
                }
                $server->enableGrantType($grant, $grants['access_token_ttl']);
            }

            return $server;
        };
        $app[ResourceServer::class] = function($app) {
            return new ResourceServer(
                $app[AccessTokenRepositoryInterface::class],
                $app['public_key']
            );
        };
        $app['resource_server_middleware'] = function($app) {
            $mw = Http\Middleware\wrap(new ResourceServerMiddleware($app[ResourceServer::class]));
            return $app['marshal_errors']
                ? new Middleware\MarshalErrorsMiddleware($mw)
                : $mw;
        };
        $app['grants'] = [];
        $app['marshal_errors'] = true;
        $app['private_key'] = null;
        $app['public_key'] = null;

        $app->routes(function($routes) {
            $routes->post('/access_token', function($req, AuthorizationServer $server) {
                try {
                    return $server->respondToAccessTokenRequest($req, $this->response(200));
                } catch (OAuthServerException $exception) {
                    return $exception->generateHttpResponse($this->response(500));
                }
            });
        });
    }
}
