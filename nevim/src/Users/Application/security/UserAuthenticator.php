<?php

declare(strict_types=1);

namespace Ondra\App\Users\Application\security;

use Nette\Security\AuthenticationException;
use Nette\Security\Authenticator;
use Nette\Security\IdentityHandler;
use Nette\Security\IIdentity;
use Nette\Security\Passwords;
use Nette\Security\SimpleIdentity;
use Ondra\App\Users\Application\IUserReadRepository;

final class UserAuthenticator implements Authenticator, IdentityHandler
{
	public function __construct(
		private readonly IUserReadRepository $repository,
		private readonly Passwords $passwords,
	) {
	}
	public function authenticate(string $username, string $password): SimpleIdentity
	{
		$passwordHash = $this->repository->getPasswordHash($username);
		if (! $passwordHash) {
			throw new AuthenticationException('user does not exist', 0);
		}
		if (! $this->passwords->verify($password, $passwordHash)) {
			throw new AuthenticationException('wrong password', 1);
		}
		return $this->repository->getIdentityByUsername($username);
	}
	function sleepIdentity(IIdentity $identity): IIdentity
	{
		return new SimpleIdentity($this->repository->getAuthtoken($identity->getId()));
	}

	function wakeupIdentity(IIdentity $identity): ?IIdentity
	{
		return $this->repository->getIdentityByAuthtoken($identity->getId());
	}
}
