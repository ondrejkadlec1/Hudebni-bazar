<?php

namespace Ondra\App\Users\Application\Command;

use Ondra\App\Shared\Application\Command\CommandRequest;
class ChangePasswordCommandRequest implements CommandRequest
{
    public int $id;
    public string $newHashedPassword;

    public function __construct(int $id, string $newHashedPassword)
    {
        $this->id = $id;
        $this->newHashedPassword = $newHashedPassword;
    }
}