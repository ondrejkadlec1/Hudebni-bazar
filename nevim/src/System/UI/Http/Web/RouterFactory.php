<?php

declare(strict_types=1);

namespace Ondra\App\System\UI\Http\Web;

use Nette\Application\Routers\RouteList;
use Nette\StaticClass;

final class RouterFactory
{
	use StaticClass;

	public static function createRouter(): RouteList
	{
		$router = new RouteList();
		$router->addRoute('', 'System:Home:default');
		$router->addRoute('[<module>/]<presenter>/<action>[/<id>]', 'Home:default');
		return $router;
	}
}
