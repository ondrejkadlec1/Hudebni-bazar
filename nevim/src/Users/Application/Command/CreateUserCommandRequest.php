<?php

namespace Ondra\App\Users\Application\Command;

use Ondra\App\Shared\Application\Command\CommandRequest;
class CreateUserCommandRequest implements CommandRequest
{
    public $username;
    public $hashedPassword;
    public $email;

    /**
     * @param $username
     * @param $hashedPassword
     * @param $email
     */
    public function __construct($username, $hashedPassword, $email)
    {
        $this->username = $username;
        $this->hashedPassword = $hashedPassword;
        $this->email = $email;
    }
}