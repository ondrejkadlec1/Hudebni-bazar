<?php

declare(strict_types=1);

namespace Ondra\App\Adverts\Application\Command\Handlers;

use Ondra\App\Adverts\Application\Command\Messages\DeleteAdvertCommandRequest;
use Ondra\App\Adverts\Application\Resources\AdvertResource;
use Ondra\App\Adverts\Infrastructure\DatabaseAdvertWriteRepository;
use Ondra\App\Shared\Application\Autowired;
use Ondra\App\Shared\Application\Exceptions\MissingContentException;
use Ondra\App\Shared\Application\Exceptions\PermissionException;
use Ondra\App\Shared\Application\Roles\RoleAssigner;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class DeleteAdvertCommandHandler implements Autowired
{
    public function __construct(
        private readonly DatabaseAdvertWriteRepository $repository,
        private readonly RoleAssigner                  $roleAssigner,
    )
    {
    }

    public function __invoke(DeleteAdvertCommandRequest $command): void
    {
        $advert = $this->repository->getById($command->id);
        if ($advert === null) {
            throw new MissingContentException('advert does not exist', 0);
        }
        if ($this->roleAssigner->isAllowed(new AdvertResource($advert->getSeller()->getId()), 'delete')) {
            $this->repository->delete($advert->getId());
            return;
        }
        throw new PermissionException('user is not the owner', 1);
    }
}
