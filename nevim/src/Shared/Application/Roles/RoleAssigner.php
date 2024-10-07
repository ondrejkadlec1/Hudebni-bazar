<?php

declare(strict_types=1);

namespace Ondra\App\Shared\Application\Roles;

use Nette\Security\Permission;
use Nette\Security\Resource;
use Nette\Security\Role;
use Nette\Security\User;
use Ondra\App\Users\Application\security\AuthorizatorFactory;

class RoleAssigner
{
	private Role|string $role;
	private Permission $acl;
	public function __construct(User $user)
	{
		$this->acl = AuthorizatorFactory::create();
		switch ($user->getRoles()) {
			case ['seller']: $this->role = new SellerRole($user->getId());
				break;
			default: $this->role = 'guest';
		}
	}
	public function isAllowed(Resource|string $resource, string $priviledge): bool
	{
		return $this->acl->isAllowed($this->role, $resource, $priviledge);
	}
}
