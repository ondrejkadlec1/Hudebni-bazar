<?php

declare(strict_types=1);

namespace Ondra\App\Users\Application\Command\Messages;

use Ondra\App\Shared\Application\Command\CommandRequest;

final readonly class ChangePasswordCommandRequest implements CommandRequest
{
	public function __construct(public string $id, public string $newPassword, public string $oldPassword)
	{
	}
}
