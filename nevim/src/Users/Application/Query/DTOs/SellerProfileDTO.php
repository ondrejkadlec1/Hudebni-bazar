<?php

declare(strict_types=1);

namespace Ondra\App\Users\Application\Query\DTOs;

final readonly class SellerProfileDTO
{
	public function __construct(public string $username, public string $description)
	{
	}
}
