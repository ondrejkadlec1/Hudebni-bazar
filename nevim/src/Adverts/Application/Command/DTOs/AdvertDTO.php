<?php

declare(strict_types=1);

namespace Ondra\App\Adverts\Application\Command\DTOs;

final readonly class AdvertDTO
{
	public function __construct(
		public ?string $id = null,
        public string $name,
        public int $stateId,
        public int $price,
        public int $subsubcategoryId,
        public int $quantity,
        public array $images,
        public string $details,
        public ?string $brand = null,
	) {
	}
}
