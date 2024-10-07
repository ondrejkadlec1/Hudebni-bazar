<?php

declare(strict_types=1);

namespace Ondra\App\Adverts\Application\Query\Messages\Response;

use Ondra\App\Adverts\Application\Query\DTOs\AdvertDetailDTO;
use Ondra\App\Shared\Application\Query\Query;

final readonly class GetAdvertResponse implements Query
{
	public function __construct(public AdvertDetailDTO $dto)
	{
	}

	public function getImageNames(): array
	{
		return $this->dto->getImageNames();
	}
}
