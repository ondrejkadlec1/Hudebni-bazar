<?php

namespace Ondra\App\Users\Application\Command;

use Ondra\App\Shared\Application\Autowired;
use Ondra\App\Users\Infrastructure\IUserRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class ChangePasswordCommandHandler implements Autowired
{
private IUserRepository $repository;

    /**
     * @param IUserRepository $repository
     */
    public function __construct(IUserRepository $repository)
    {
        $this->repository = $repository;
    }
    public function __invoke(ChangePasswordCommandRequest $command): void{
        $this->repository->changePassword($command->id, $command->newHashedPassword);
    }
}