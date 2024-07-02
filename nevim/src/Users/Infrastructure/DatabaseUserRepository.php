<?php

namespace Ondra\App\Users\Infracstructure\Repositories;

use Nette\Database\Connection;
use Nette\Database\Explorer;

final class DatabaseUserRepository implements IUserRepository
{
    public function __construct(
        private readonly Connection $connection,
        private readonly Explorer $explorer,
    ){
    }
    public function getUserById(int $id)
    {
        return $this->explorer->fetch('SELECT * FROM users WHERE id = ?', $id);
    }

    public function getUserByUsername(string $username)
    {
        return $this->explorer->fetch('SELECT * FROM users WHERE username = ?', $username);
    }

    public function getUserByEmail(string $email)
    {
        return $this->explorer->table('users')->select('username')->where('username', $email)->fetch();
    }

    public function createNewUser(string $username, string $hashedPassword, string $email): void
    {
        $query = "INSERT INTO users(username, password, email, created_at) VALUES(?, ?, ?, ?)";
        $this->connection->query($query, $username, $hashedPassword, $email, date(DATE_ATOM));
    }

    public function changePassword(int $id, string $newHashedPassword): void
    {
        $query = "UPDATE users SET password = ? WHERE id = ?";
        $this->connection->query($query, $newHashedPassword, $id);
    }
}