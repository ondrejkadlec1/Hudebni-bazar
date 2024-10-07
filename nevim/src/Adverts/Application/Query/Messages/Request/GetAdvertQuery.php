<?php

declare(strict_types=1);

namespace Ondra\App\Adverts\Application\Query\Messages\Request;

use Ondra\App\Shared\Application\Query\Query;

final readonly class GetAdvertQuery implements Query
{

	public function __construct(public string $id)
	{
	}
}
