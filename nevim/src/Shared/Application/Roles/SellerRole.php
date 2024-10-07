<?php

declare(strict_types=1);

namespace Ondra\App\Shared\Application\Roles;

use Nette\Security\Role;

class SellerRole implements Role
{
	public function __construct(private string $id)
	{
	}

	public function getId(): string
	{
		return $this->id;
	}

	public function getRoleId(): string
	{
		return 'seller';
	}
}
