<?php

require_once __DIR__ . '/../../vendor/autoload.php';

chdir(__DIR__);
$app = new Krak\Lava\App();
$app->with(new Krak\LavaOAuth2\OAuth2InternalPackage());
$app->runConsole();
