<?php

namespace Krak\LavaOAuth2;

use Krak\Lava;
use Doctrine;

class DoctrinePackage implements Lava\Package
{
    public function with(Lava\App $app) {
        $app[Doctrine\DBAL\Connection::class] = function() {
            return Doctrine\DBAL\DriverManager::getConnection([
                'dbname' => getenv('DB_NAME'),
                'user' => getenv('DB_USER'),
                'password' => getenv('DB_PASS'),
                'host' => getenv('DB_HOST'),
                'driver' => 'pdo_mysql',
                'charset' => 'utf8',
            ]);
        };
        $app[Doctrine\ORM\EntityManagerInterface::class] = function($app) {
            $config = Doctrine\ORM\Tools\Setup::createConfiguration($app['debug']);
            $config->setMetadataDriverImpl(new Doctrine\ORM\Mapping\Driver\SimplifiedYamlDriver([
                __DIR__ . '/Resources/doctrine/mapping' => Model::class,
            ]));
            $config->setAutoGenerateProxyClasses(
                Doctrine\Common\Proxy\AbstractProxyFactory::AUTOGENERATE_EVAL
            );
            $config->addEntityNamespace('OAuth2', Model::class);

            return Doctrine\ORM\EntityManager::create(
                $app[Doctrine\DBAL\Connection::class],
                $config
            );
        };
        $app->alias(Doctrine\DBAL\Connection::class, 'db');
        $app->alias(Doctrine\ORM\EntityManagerInterface::class,
            Doctrine\ORM\EntityManager::class,
            Doctrine\Common\Persistence\ObjectManager::class,
            'em'
        );

        $app->wrap('console', function($console, $app) {
            $console->mergeHelperSets(Doctrine\ORM\Tools\Console\ConsoleRunner::createHelperSet($app['em']));
            Doctrine\ORM\Tools\Console\ConsoleRunner::addCommands($console);
            Doctrine\DBAL\Migrations\Tools\Console\ConsoleRunner::addCommands($console);
            return $console;
        });
    }
}
