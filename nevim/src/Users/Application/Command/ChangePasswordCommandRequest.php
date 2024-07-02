<?php

namespace Ondra\App\System\Application\Command;

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