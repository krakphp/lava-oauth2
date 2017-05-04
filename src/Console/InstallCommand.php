<?php

namespace Krak\LavaOAuth2\Console;

use Krak\Lava;
use Symfony\Component\Console\Input\ArrayInput;

class InstallCommand extends Lava\Console\Command
{
    public function define($def) {
        $def->name('lava-oauth2:install')
            ->description('Install the Lava OAuth2 package.');
    }

    public function handle() {
        $oauth2_app = $this->getApp()['krak.lava_oauth2'];
        $migrate_command = $oauth2_app['console']->find('migrations:migrate');

        $migrate_input = new ArrayInput([
            'command' => 'migrations:migrate',
            '--configuration' => __DIR__ . '/../Resources/migrations.yml'
        ]);
        $migrate_input->setInteractive(false);
        $migrate_command->run($migrate_input, $this->output());
    }
}
