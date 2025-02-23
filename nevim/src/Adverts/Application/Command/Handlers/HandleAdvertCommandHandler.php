<?php

declare(strict_types=1);

namespace Ondra\App\Adverts\Application\Command\Handlers;

use Nette\Http\FileUpload;
use Nette\Security\User;
use Ondra\App\Adverts\Application\Command\DTOs\AdvertDTO;
use Ondra\App\Adverts\Application\Command\Messages\HandleAdvertCommandRequest;
use Ondra\App\Adverts\Application\IAdvertWriteRepository;
use Ondra\App\Adverts\Application\Resources\AdvertResource;
use Ondra\App\Adverts\Domain\Advert;
use Ondra\App\Adverts\Domain\Item;
use Ondra\App\Adverts\Domain\ItemImage;
use Ondra\App\Adverts\Domain\Seller;
use Ondra\App\ApplicationConfiguration;
use Ondra\App\Shared\Application\Autowired;
use Ondra\App\Shared\Application\Exceptions\MissingContentException;
use Ondra\App\Shared\Application\Exceptions\PermissionException;
use Ondra\App\Shared\Application\Roles\RoleAssigner;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class HandleAdvertCommandHandler implements Autowired
{
    public function __construct(
        private readonly IAdvertWriteRepository   $repository,
        private readonly User                     $user,
        private readonly RoleAssigner             $roleAssigner,
        private readonly ApplicationConfiguration $configuration,
    )
    {
    }

    public function __invoke(HandleAdvertCommandRequest $command): void
    {
        if ($this->roleAssigner->isAllowed('advert', 'create')) {
            $dto = $command->dto;
            if ($dto->id === null){
                $this->create($dto);
                return;
            }
            $this->update($dto);
            return;
        }
        throw new PermissionException('user is not signed in', 0);
    }

    private function create(AdvertDTO $dto): void
    {
        $itemImages = [];
        foreach ($dto->images as $imageId => $file) {
            $itemImages[] = new ItemImage($imageId, $file);
        }
        $item = new Item(
            $dto->name,
            $dto->details,
            $dto->stateId,
            $itemImages,
            $dto->lowestCategoryId,
            $this->configuration->get()['imagesPerItem'],
            $dto->brand,
        );

        $seller = new Seller($this->user->getId());
        $this->repository->save(new Advert(uniqid(), $item, $seller, $dto->price, $dto->quantity));
    }
    private function update(AdvertDTO $dto): void
    {
        $advert = $this->repository->getById($dto->id);
        if ($advert === null) {
            throw new MissingContentException('resource does not exist', 1);
        }
        if ($this->roleAssigner->isAllowed(new AdvertResource($advert->getSellerId()), 'update')) {
            $itemImages = [];
            $ids = array_filter($dto->images, static fn($var) => !$var instanceof FileUpload);
            $imageId = $ids === [] ? 0 : max($ids);
            foreach ($dto->images as $content) {
                if ($content instanceof FileUpload) {
                    $imageId += 1;
                    $itemImages[] = new ItemImage($imageId, $content);
                    continue;
                }
                $itemImages[] = new ItemImage($content);
            }

            $advert->setPrice($dto->price);
            $advert->setQuantity($dto->quantity);
            $advert->setItemName($dto->name);
            $advert->setItemBrand($dto->brand);
            $advert->setItemDetails($dto->details);
            $advert->setItemStateId($dto->stateId);
            $advert->setItemLowestCategoryId($dto->lowestCategoryId);
            $advert->setItemImages($itemImages);
            $this->repository->save($advert);
            return;
        }
        throw new PermissionException('user is not the owner', 1);
    }
}
