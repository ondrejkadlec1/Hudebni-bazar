<?php

declare(strict_types=1);

namespace Ondra\App\Users\Application\Command\Handlers;

use Exception;
use Nette\Security\AuthenticationException;
use Nette\Security\Passwords;
use Ondra\App\Shared\Application\Autowired;
use Ondra\App\Users\Application\Command\Messages\ChangePasswordCommandRequest;
use Ondra\App\Users\Application\IUserWriteRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class ChangePasswordCommandHandler implements Autowired
{
    public function __construct(
        private readonly IUserWriteRepository $repository,
        private readonly Passwords            $passwords,
    )
    {
    }

    public function __invoke(ChangePasswordCommandRequest $command): void
    {
        $user = $this->repository->getUserById($command->id);
        if ($user === null) {
            throw new Exception('user not found', 0);
        }
        if ($this->passwords->verify($command->oldPassword, $user->getPassword()) === null) {
            throw new AuthenticationException('wrong password', 1);
        }

        $user->setPassword($command->newPassword);
        $this->repository->save($user);
    }
}
