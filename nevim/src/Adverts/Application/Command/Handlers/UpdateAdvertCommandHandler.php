<?php

declare(strict_types=1);

namespace Ondra\App\Adverts\Application\Command\Handlers;

use Nette\Http\FileUpload;
use Ondra\App\Adverts\Application\Command\Messages\UpdateAdvertCommandRequest;
use Ondra\App\Adverts\Application\IAdvertWriteRepository;
use Ondra\App\Adverts\Application\Resources\AdvertResource;
use Ondra\App\Adverts\Domain\ItemImage;
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
        if (!isset($advert)) {
            throw new MissingContentException('resource does not exist', 1);
        }
		if (!$this->roleAssigner->isAllowed(new AdvertResource($advert->getSeller()->getId()), 'update')) {
			throw new PermissionException('user is not the owner', 1);
		}
        $itemImages = [];
        $ids = array_filter($dto->images, function ($var) {
            return !$var instanceof FileUpload;
        });
        $imageId = empty($ids) ? 0 : max($ids);
        foreach ($dto->images as $content) {
            if ($content instanceof FileUpload) {
                $imageId = $imageId + 1;
                $itemImages[] = new ItemImage($imageId, $content);
            }
            else {
                $itemImages[] = new ItemImage($content);
            }
        }

        $advert->setPrice($dto->price);
        $advert->setQuantity($dto->quantity);
        $advert->getItem()->setName($dto->name);
        $advert->getItem()->setBrand($dto->brand);
        $advert->getItem()->setDetails($dto->details);
        $advert->getItem()->setStateId($dto->stateId);
        $advert->getItem()->setSubsubcategoryId($dto->subsubcategoryId);
        $advert->getItem()->setItemImages($itemImages);
        $this->repository->save($advert);
	}
}
