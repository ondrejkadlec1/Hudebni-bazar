<?php

declare(strict_types=1);

namespace Ondra\App\Adverts\Application\Command\DTOs;

final readonly class AdvertDTO
{
	public function __construct(
        public string $name,
        public int $stateId,
        public string $details,
        public int $price,
        public int $quantity,
        public array $images,
        public int $subsubcategoryId,
        public ?string $brand = null,
        public ?string $id = null)
	{
	}
}
