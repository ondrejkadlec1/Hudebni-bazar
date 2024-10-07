<?php

declare(strict_types=1);

namespace Ondra\App\Adverts\Application\Command\Messages;

use Ondra\App\Shared\Application\Command\CommandRequest;

final readonly class DeleteAdvertCommandRequest implements CommandRequest
{
	public function __construct(public string $id)
	{
	}
}
