<?php

declare(strict_types=1);

namespace Ondra\App\Adverts\Application\Command\Handlers;

use Ondra\App\Adverts\Application\Command\Messages\UpdateAdvertCommandRequest;
use Ondra\App\Adverts\Application\IAdvertWriteRepository;
use Ondra\App\Adverts\Application\Resources\AdvertResource;
use Ondra\App\Shared\Application\Autowired;
use Ondra\App\Shared\Application\Exceptions\MissingContentException;
use Ondra\App\Shared\Application\Exceptions\PermissionException;
use Ondra\App\Shared\Application\Roles\RoleAssigner;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class UpdateAdvertCommandHandler implements Autowired
{
	public function __construct(
		private readonly IAdvertWriteRepository $repository,
		private readonly RoleAssigner $roleAssigner,
	) {}

	public function __invoke(UpdateAdvertCommandRequest $command): void
	{
		$dto = $command->dto;
        $advert = $this->repository->getById($dto->id);
		if (!$this->roleAssigner->isAllowed(new AdvertResource($advert->getSeller()->getId()), 'update')) {
			throw new PermissionException('user is not the owner', 1);
		}
        if (!isset($advert)) {
             throw new MissingContentException('resource does not exist', 1);
        }
        $advert->setPrice($dto->price);
        $advert->setQuantity($dto->quantity);
        $advert->getItem()->setName($dto->name);
        $advert->getItem()->setBrand($dto->brand);
        $advert->getItem()->setDetails($dto->details);
        $advert->getItem()->setStateId($dto->stateId);
        $advert->getItem()->setSubsubcategoryId($dto->subsubcategoryId);
        $this->repository->save($advert);
	}
}
