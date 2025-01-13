<?php

declare(strict_types=1);

namespace Ondra\App\Shared\Application\Roles;

use Nette\Security\Permission;
use Nette\Security\Resource;
use Nette\Security\Role;
use Nette\Security\User;
use Ondra\App\Users\Application\security\AuthorizatorFactory;

final class RoleAssigner
{
	private readonly Role|string $role;
	private readonly Permission $acl;
	public function __construct(User $user)
	{
		$this->acl = AuthorizatorFactory::create();
		$this->role = match ($user->getRoles()) {
      ['seller'] => new SellerRole($user->getId()),
      default => 'guest',
  };
	}
	public function isAllowed(Resource|string $resource, string $priviledge): bool
	{
		return $this->acl->isAllowed($this->role, $resource, $priviledge);
	}
}
