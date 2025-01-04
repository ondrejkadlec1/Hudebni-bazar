<?php

declare(strict_types=1);

namespace Ondra\App\Users\Application\Query\Messages;

use Ondra\App\Shared\Application\Query\Query;
use Ondra\App\Users\Application\Query\DTOs\SellerProfileDTO;

final readonly class GetSellerNameResponse implements Query
{
	public function __construct(public string $name)
	{
	}
}
