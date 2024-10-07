<?php

declare(strict_types=1);

namespace Ondra\App\Users\Application\Command\Messages;

use Ondra\App\Shared\Application\Command\CommandRequest;

final readonly class CreateUserCommandRequest implements CommandRequest
{
	public function __construct(public string $usename, public string $email, public string $password)
	{
	}
}
