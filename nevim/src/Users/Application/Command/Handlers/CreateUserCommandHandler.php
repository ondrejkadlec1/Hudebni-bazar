<?php

declare(strict_types=1);

namespace Ondra\App\Users\Application\Command\Handlers;

use Ondra\App\Shared\Application\Autowired;
use Ondra\App\Shared\Application\Exceptions\ValidationException;
use Ondra\App\Users\Application\Command\Messages\CreateUserCommandRequest;
use Ondra\App\Users\Application\IUserWriteRepository;
use Ondra\App\Users\Domain\User;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class CreateUserCommandHandler implements Autowired
{
    public function __construct(private readonly IUserWriteRepository $repository)
    {
    }

    public function __invoke(CreateUserCommandRequest $command): void
    {
        if ($this->repository->getUserByUsername($command->usename) !== null) {
            throw new ValidationException('username has benn used', 0);
        }
        if ($this->repository->getUserByEmail($command->email) !== null) {
            throw new ValidationException('email has been used', 1);
        }
        $this->repository->save(new User($command->usename, $command->email, $command->password, uniqid()));
    }
}
