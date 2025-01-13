<?php

declare(strict_types=1);

namespace Ondra\App\Users\Application\Query\Messages;

use Ondra\App\Shared\Application\Query\Query;
use Ondra\App\Users\Application\Query\DTOs\IProfileDTO;

final readonly class GetMyProfileResponse implements Query
{
	public function __construct(public IProfileDTO $dto)
	{
	}
}
