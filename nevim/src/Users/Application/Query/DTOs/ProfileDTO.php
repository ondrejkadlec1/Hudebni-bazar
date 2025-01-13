<?php

declare(strict_types=1);

namespace Ondra\App\Users\Application\Query\DTOs;

final readonly class ProfileDTO implements IProfileDTO
{
	public function __construct(public string $username, public string $createdAt)
	{
	}
}
