<?php

namespace Ondra\App\Users\Application\Command;

use Ondra\App\Shared\Application\Autowired;
use Ondra\App\Users\Infrastructure\IUserRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class CreateUserCommandHandler implements Autowired
{
    private IUserRepository $repository;
    public function __construct(IUserRepository $repository){
        $this->repository = $repository;
    }
    private $nameUsed;
    private $emailUsed;
    public function __invoke(CreateUserCommandRequest $command): void
    {
            $this->nameUsed = $this->repository->getUserByUsername($command->username);
            if ($this->nameUsed) {
                throw new ValidationException("Toto uživatelsé jméno už někdo použil.");
            }
            $this->emailUsed = $this->repository->getUserByEmail($command->email);
            if ($this->emailUsed) {
                throw new ValidationException("Tento email už někdo použil.");
            }
            else{
            $this->repository->createNewUser($command->username, $command->hashedPassword, $command->email);
            }

    }
}