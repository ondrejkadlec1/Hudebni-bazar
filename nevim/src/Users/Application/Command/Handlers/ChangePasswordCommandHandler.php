<?php

declare(strict_types=1);

namespace Ondra\App\Users\Application\Command\Handlers;

use Nette\Security\AuthenticationException;
use Nette\Security\Passwords;
use Ondra\App\Shared\Application\Autowired;
use Ondra\App\Users\Application\Command\Messages\ChangePasswordCommandRequest;
use Ondra\App\Users\Application\IUserWriteRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class ChangePasswordCommandHandler implements Autowired
{
	public function __construct(
		private readonly IUserWriteRepository $repository,
		private readonly Passwords $passwords,
	) {
	}

	public function __invoke(ChangePasswordCommandRequest $command): void
	{
		$user = $this->repository->getUserById($command->id);
		if (!isset($user)) {
			throw new \Exception('user not found', 0);
		}
		if (! $this->passwords->verify($command->oldPassword, $user->getPassword())) {
			throw new AuthenticationException('wrong password', 1);
		}

		$user->setPassword($this->passwords->hash($command->newPassword));
		$this->repository->save($user);
	}
}
