<?php

declare(strict_types=1);

namespace Ondra\App\Adverts\Application\Query\DTOs;

final readonly class AdvertDetailDTO
{
	public function __construct(
		public string $id,
		public string $name,
		public int $price,
		public int $quantity,
		public int $stateId,
		public string $state,
		public string $sellerName,
		public string $sellerId,
		public string $details,
		public string $createdAt,
		public array $imageNames,
		public array $imageIds,
		public array $categories,
		public ?string $brand,
		public ?string $lastUpdate,
		public ?string $mainImageName,
	) {
	}
}
