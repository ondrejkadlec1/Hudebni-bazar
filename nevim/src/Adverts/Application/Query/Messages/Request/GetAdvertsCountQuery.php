<?php

declare(strict_types=1);

namespace Ondra\App\Adverts\Application\Query\Messages\Request;

use Ondra\App\Adverts\Application\Query\DTOs\SearchCriteria;
use Ondra\App\Shared\Application\Query\Query;

final readonly class GetAdvertsCountQuery implements Query
{
	public function __construct(public SearchCriteria $criteria)
	{
	}
}
