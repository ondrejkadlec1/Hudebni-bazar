<?php

namespace Ondra\App\System\Application\Command;

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