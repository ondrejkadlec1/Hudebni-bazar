<?php

declare(strict_types=1);

namespace Ondra\App\Users\Application\security;

use Nette\Security\Permission;

final class AuthorizatorFactory
{
	public static function create(): Permission
	{
		$acl = new Permission();
		$acl->addRole('guest');
		$acl->addRole('user');
		$acl->addRole('seller');
		$acl->addRole('admin');

		$acl->addResource('advert');
		$acl->addResource('profile');

		$advertOwnerAssertion = static function (Permission $acl) : bool {
      $role = $acl->getQueriedRole();
      $resource = $acl->getQueriedResource();
      return $role->getId() === $resource->getSellerId();
  };

		$acl->allow('seller', 'advert', ['update', 'delete'], $advertOwnerAssertion);
		$acl->allow('seller', 'advert', 'create');
		$acl->allow('admin', 'advert', ['create', 'update', 'delete']);

		return $acl;
	}
}
