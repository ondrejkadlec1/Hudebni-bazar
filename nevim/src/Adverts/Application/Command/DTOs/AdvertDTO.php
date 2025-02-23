<?php

declare(strict_types=1);

namespace Ondra\App\Adverts\Application\Command\DTOs;

final readonly class AdvertDTO
{
	public function __construct(
        public string $name,
        public int $stateId,
        public int $price,
        public int $lowestCategoryId,
        public int $quantity,
        public array $images,
        public string $details,
        public ?string $id = null,
        public ?string $brand = null,
	) {
	}
}
