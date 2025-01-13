<?php

declare(strict_types=1);

namespace Ondra\App\Adverts\Application\Query\DTOs;

final readonly class AdvertOverviewDTO
{
	public function __construct(
		public string $id,
		public string $name,
		public int $price,
		public string $state,
		public string $sellerName,
		public string $sellerId,
		public string $details,
		public string $subsubcategory,
		public string $createdAt,
		public ?string $brand,
		public ?string $lastUpdate,
		public ?string $mainImageName,
	) {
	}
}
