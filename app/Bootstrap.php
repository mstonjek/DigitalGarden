<?php

declare(strict_types=1);

namespace App;

use Nette\Bootstrap\Configurator;

class Bootstrap
{
    public static function boot(): Configurator
    {
        $configurator = new Configurator();
        $appDir = dirname(__DIR__);

        // Debug mode ONLY via environment. Never hardcoded to true on production,
        // otherwise Tracy would leak stack traces and source code to visitors.
        $debugMode = filter_var(getenv('DEBUG_MODE'), FILTER_VALIDATE_BOOLEAN);
        $configurator->setDebugMode($debugMode);

        $configurator->enableTracy($appDir . '/log');

        $configurator->setTimeZone('Europe/Prague');
        $configurator->setTempDirectory($appDir . '/temp');

        $configurator->createRobotLoader()
            ->addDirectory(__DIR__)
            ->register();

        $configurator->addConfig($appDir . '/config/common.neon');
        $configurator->addConfig($appDir . '/config/services.neon');

        $localConfig = $appDir . '/config/local.neon';
        if (is_file($localConfig)) {
            // Local development overrides (gitignored, never shipped in the Docker image).
            $configurator->addConfig($localConfig);
        } else {
            // Production (e.g. Coolify): all secrets come from environment variables,
            // so missing files fail fast with a clear error instead of obscure DI errors.
            $configurator->addStaticParameters(self::loadEnvParameters());
        }

        return $configurator;
    }

    /**
     * @return array<string, mixed>
     */
    private static function loadEnvParameters(): array
    {
        $required = [
            'GITHUB_CLIENT_ID',
            'GITHUB_CLIENT_SECRET',
            'GITHUB_REDIRECT_URI',
            'DB_HOST',
            'DB_USER',
            'DB_PASSWORD',
            'DB_NAME',
        ];

        $missing = [];
        $env = [];
        foreach ($required as $name) {
            $value = getenv($name);
            if ($value === false || $value === '') {
                $missing[] = $name;
            } else {
                $env[$name] = $value;
            }
        }

        if ($missing !== []) {
            throw new \RuntimeException(
                'Missing required environment variables: ' . implode(', ', $missing)
            );
        }

        return [
            'github_client_id' => $env['GITHUB_CLIENT_ID'],
            'github_client_secret' => $env['GITHUB_CLIENT_SECRET'],
            'github_redirect_uri' => $env['GITHUB_REDIRECT_URI'],
            'doctrine' => [
                'host' => $env['DB_HOST'],
                'user' => $env['DB_USER'],
                'password' => $env['DB_PASSWORD'],
                'dbname' => $env['DB_NAME'],
            ],
        ];
    }
}
