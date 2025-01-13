<?php

declare(strict_types=1);

namespace Ondra\App\Users\Domain;

final class User
{
	public function __construct(private readonly string $username, private readonly string $email, private string $password, private readonly string $id)
	{
	}

	public function getId(): string
	{
		return $this->id;
	}

	public function setPassword(string $password): void
	{
		$this->password = $password;
	}

	public function getUsername(): string
	{
		return $this->username;
	}

	public function getEmail(): string
	{
		return $this->email;
	}

	public function getPassword(): string
	{
		return $this->password;
	}
}
