<?php

namespace Ondra\App\System\Application\Command;

use Ondra\App\System\Application\Repositories\UserRepository;

class ChangePasswordCommandHandler


{
private UserRepository $repository;

    /**
     * @param UserRepository $repository
     */
    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }
    public function __invoke(ChangePasswordCommandRequest $command){
        $this->repository->changePassword($command->id, $command->newHashedPassword);
    }
}