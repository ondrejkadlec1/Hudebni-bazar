<?php

declare(strict_types=1);

namespace Ondra\App\Users\Application;

use Ondra\App\Users\Domain\User;

interface IUserWriteRepository
{
	public function getUserById(string $id): ?User;
	public function getUserByEmail(string $email): ?User;
	public function getUserByUsername(string $username): ?User;
	public function save(User $user): void;
}
