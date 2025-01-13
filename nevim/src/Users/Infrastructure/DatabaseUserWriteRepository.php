<?php

declare(strict_types=1);

namespace Ondra\App\Users\Infrastructure;

use Nette\Database\Connection;
use Nette\Database\Explorer;
use Nette\Database\Row;
use Nette\Utils\Random;
use Ondra\App\Users\Application\IUserWriteRepository;
use Ondra\App\Users\Domain\User;

final class DatabaseUserWriteRepository implements IUserWriteRepository
{
	public function __construct(
		private readonly Connection $connection,
		private readonly Explorer $explorer,
	) {
	}
	public function createUser(?Row $userData): ?User
	{
		return $userData !== null ? new User($userData->username, $userData->email, $userData->password, $userData->id) : null;
	}
	public function getUserById(string $id): ?User
	{
		return $this->createUser($this->explorer->fetch('SELECT * FROM users WHERE id = ?', $id));
	}
	public function getUserByUsername(string $username): ?User
	{
		return $this->createUser($this->explorer->fetch('SELECT * FROM users WHERE username = ?', $username));
	}
	public function getUserByEmail(string $email): ?User
	{
		return $this->createUser($this->explorer->fetch('SELECT * FROM users WHERE username = ?', $email));
	}

	public function save(User $user): void
	{
		if ($this->explorer->fetch("SELECT id FROM users WHERE id = ?", $user->getId()) !== null) {
			$query = "UPDATE users SET password = ? WHERE id = ?";
			$this->connection->query($query, $user->getPassword(), $user->getId());
		} else {
			$query = "INSERT INTO users(username, password, email, created_at, authtoken, id) VALUES(?, ?, ?, ?, ?, ?)";
			$this->connection->query(
				$query,
				$user->getUsername(),
				$user->getPassword(),
				$user->getEmail(),
				date(DATE_ATOM),
				Random::generate(13),
				uniqid(),
			);
		}
	}
}
