<?php

use Dotenv\Dotenv;

require __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv::createImmutable(__DIR__);
$envConfiguration = $dotenv->load();

return
	[
		'paths' => [
			'migrations' => '%%PHINX_CONFIG_DIR%%/db/migrations',
			'seeds' => '%%PHINX_CONFIG_DIR%%/db/seeds',
		],
		'environments' => [
			'default_migration_table' => $envConfiguration['PHINX_TABLE'],
			'default_environment' => 'production',
			'production' => [
				'adapter' => 'pgsql',
				'host' => $envConfiguration['POSTGRES_HOST'],
				'name' => $envConfiguration['POSTGRES_DB'],
				'user' => $envConfiguration['POSTGRES_USER'],
				'pass' => $envConfiguration['POSTGRES_PASSWORD'],
				'port' => $envConfiguration['POSTGRES_PORT'],
				'charset' => 'utf8',
			],
		],
		'version_order' => 'creation',
	];
