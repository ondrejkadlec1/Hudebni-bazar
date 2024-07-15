<?php

namespace Ondra\App\Users\Infrastructure;

interface IUserRepository
{
    public function getUserById(int $id);

    public  function getUserByUsername(string $username);

    public  function getUserByEmail(string $email);

    public function createNewUser(string $username, string $hashedPassword, string $email): void;

    public function changePassword(int $id, string $newHashedPassword): void;
}