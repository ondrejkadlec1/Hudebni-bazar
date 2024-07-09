<?php

namespace Ondra\App\Adverts\Application\Command;
use Nette\Security\User;
use Ondra\App\Adverts\Application\IAdvertRepository;
use Ondra\App\Adverts\Domain\Advert;
use Ondra\App\Adverts\Domain\Item;
use Ondra\App\Adverts\Domain\ItemImage;
use Ondra\App\Adverts\Domain\Seller;
use Ondra\App\Shared\Application\Autowired;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class CreateAdvertCommandHandler implements Autowired
{
    private IAdvertRepository $advertRepository;
    private User $user;
    public function __construct(IAdvertRepository $advertRepository, User $user)
    {
        $this->advertRepository = $advertRepository;
        $this->user = $user;
    }

    public function __invoke(CreateAdvertCommandRequest $command): void
    {
        $dto = $command->getDto();

        $itemImages = [];
        foreach ($dto->getImages() as $file){
            $itemImage = new ItemImage($file);
            $itemImages[] = $itemImage;
            $file->move($_ENV['ITEM_IMAGES_DIRECTORY'] . $itemImage->getName());
        }
        $item = new Item(uniqid(), $dto->getName(), $dto->getStateId(), $dto->getDetails(), $itemImages, $dto->getSubsubcategoryId());

        $seller = new Seller($this->user->getId());
        $this->advertRepository->save(new Advert(uniqid(), $item,  $seller, $dto->getPrice(), $dto->getQuantity()));
    }
}