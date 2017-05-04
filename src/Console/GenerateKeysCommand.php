<?php

namespace Krak\LavaOAuth2\Console;

use Krak\Lava;

class GenerateKeysCommand extends Lava\Console\Command
{
    public function define($def) {
        $def->name('lava-oauth2:generate-keys')
            ->description('Generate a set of public/private keys')
            ->requiredArgument('output-path', 'The path export the keys into.')
            ->valueOption('key-size', 'k', 'The size of the key to make, defaults to 1024');
    }

    public function handle() {
        $output_path = $this->argument('output-path');
        $key_size = (int) $this->option('key-size') ?: 1024;

        $this->info("Generating RSA key pair");

        $output_path = rtrim($output_path, '/');
        $private_output_path = $output_path . '/oauth-private.key';
        $public_output_path = $output_path . '/oauth-public.key';
        $cmd = sprintf('openssl genrsa -out %s %d', $private_output_path, $key_size);
        $this->writeln($cmd);
        `$cmd`;
        $cmd = sprintf('openssl rsa -in %s -pubout -out %s', $private_output_path, $public_output_path);
        $this->writeln($cmd);
        `$cmd`;

        $this->info("Key pair generated at: $output_path");
    }
}
