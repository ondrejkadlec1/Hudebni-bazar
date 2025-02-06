<?php

declare(strict_types=1);

namespace Ondra\App\Users\Application\Query\Handlers;

use Nette\Security\User;
use Ondra\App\Shared\Application\Autowired;
use Ondra\App\Shared\Application\Exceptions\MissingContentException;
use Ondra\App\Users\Application\Query\Messages\GetMyProfileQuery;
use Ondra\App\Users\Application\Query\Messages\GetMyProfileResponse;
use Ondra\App\Users\Infrastructure\DatabaseUserReadRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class GetMyProfileQueryHandler implements Autowired
{
    public function __construct(
        private readonly DatabaseUserReadRepository $repository,
        private readonly User                       $user,
    )
    {
    }

    public function __invoke(GetMyProfileQuery $_): GetMyProfileResponse
    {
        $response = $this->repository->getAnyProfile($this->user->getId());
        if ($response === null) {
            throw new MissingContentException('user does not exist');
        }
        return new GetMyProfileResponse($response);
    }
}
