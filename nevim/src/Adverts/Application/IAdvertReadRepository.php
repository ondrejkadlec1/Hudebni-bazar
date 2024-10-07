<?php

declare(strict_types=1);

namespace Ondra\App\Adverts\Application;

use Ondra\App\Adverts\Application\Query\DTOs\AdvertDetailDTO;
use Ondra\App\Adverts\Application\Query\DTOs\SearchCriteria;

interface IAdvertReadRepository
{
	public function getDetail(string $id): ?AdvertDetailDTO;
	public function getOverviews(SearchCriteria $criteria): array;
	public function getCount(SearchCriteria $criteria): int;
}
