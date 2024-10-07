<?php

declare(strict_types=1);

namespace Ondra\App\Adverts\Application\Command\Messages;

use Ondra\App\Adverts\Application\Command\DTOs\AdvertDTO;
use Ondra\App\Shared\Application\Command\CommandRequest;

final readonly class UpdateAdvertCommandRequest implements CommandRequest
{
	public AdvertDTO $dto;
	public function __construct(string $id, string $name, int $stateId, int $price, int $subsubcategoryId, int $quantity = 1, string $details = "", array $images = [], ?string $brand = null)
	{
		$this->dto = new AdvertDTO($name, $stateId, $details, $price, $quantity, $images, $subsubcategoryId, $brand, $id);
	}
}
