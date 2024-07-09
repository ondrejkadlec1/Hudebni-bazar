<?php

declare(strict_types=1);

namespace Ondra\App;

use Dotenv\Dotenv;
use Nette\Bootstrap\Configurator;

final class Bootstrap
{
    public static function boot(): Configurator
    {
        $configurator = new Configurator();
        $appDir = dirname(__DIR__);

        define('__WWW_DIR__', $appDir . '/www');

        $configurator->setDebugMode(true);
        $configurator->enableTracy($appDir . '/log');

        $configurator->setTempDirectory($appDir . '/temp');

        $configurator->createRobotLoader()
            ->addDirectory(__DIR__)
            ->register();

        $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
        $envConfiguration = $dotenv->load();

        $configurator->addDynamicParameters([
            'env' => $envConfiguration,
        ]);

        $configurator->addConfig($appDir . '/config/common.neon');
        $configurator->addConfig($appDir . '/config/services.neon');
        $configurator->addConfig($appDir . '/config/local.neon');
        return $configurator;
    }
}