<?php

declare(strict_types=1);

namespace Ondra\App\Adverts\Application\Query\DTOs;

final class SearchCriteria
{
	public static string $asc = "asc";
	public static string $desc = "desc";
	public static string $orderByPrice = "price";
	public static string $orderByDate = "date";
	public function __construct(
		public readonly ?int $limit = null,
        public readonly int $offset = 0,
        public readonly string $orderBy = 'date',
        public readonly string $direction = 'desc',
        public readonly ?int $categoryId = null,
        public readonly ?string $sellerId = null,
        public readonly ?int $maxPrice = null,
        public readonly ?int $minPrice = null,
        public readonly ?array $stateIds = null,
        public readonly ?array $brands = null,
        public readonly ?string $expression = null,
	) {
	}
}
