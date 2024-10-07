<?php

declare(strict_types=1);

namespace Ondra\App\Adverts\Application\Query\Messages\Request;

use Ondra\App\Shared\Application\Query\Query;

final readonly class GetItemImageQuery implements Query
{
	public function __construct(public string $name)
	{
	}
}
