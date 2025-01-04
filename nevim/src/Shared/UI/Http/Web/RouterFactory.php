<?php

declare(strict_types=1);

namespace Ondra\App\Shared\UI\Http\Web;

use Nette\Application\Routers\RouteList;
use Nette\StaticClass;

final class RouterFactory
{
	use StaticClass;

	public static function createRouter(): RouteList
	{
		$router = new RouteList();
		$router->addRoute('', 'Adverts:Home:default');
        $router->addRoute('image/<imageName>', 'Adverts:Browse:image');
        $router->addRoute('images/<imageName>', 'Adverts:Detail:images');
		$router->addRoute('[<module>/]<presenter>/<action>[/<id>]', 'Home:default');
		return $router;
	}
}
