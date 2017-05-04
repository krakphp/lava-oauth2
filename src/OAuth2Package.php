<?php

namespace Krak\LavaOAuth2;

use Krak\Lava;
use TravelBudget\Api;
use Doctrine;

class OAuth2Package extends Lava\AbstractPackage
{
    public function bootstrap(Lava\App $app) {
        $app['krak.lava_oauth2']['debug'] = $app['debug'];
    }

    public function with(Lava\App $app) {
        $oauth2_app = new Lava\App();
        $oauth2_app->with(new OAuth2InternalPackage());
        $app['krak.lava_oauth2'] = $oauth2_app;
        $app->commands([
            Console\GenerateKeysCommand::class,
            Console\InstallCommand::class
        ]);
    }
}
