<?php

namespace Ondra\App\Adverts\Application\Command;
use Nette\Security\User;
use Ondra\App\Adverts\Application\IAdvertRepository;
use Ondra\App\Adverts\Domain\Advert;
use Ondra\App\Adverts\Domain\Item;
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
        $this->AdvertRepository = $AdvertRepository;
        $this->user = $user;
    }

    public function __invoke(CreateAdvertCommandRequest $command): void
    {   $dto = $command->dto;
        $product = new Item(uniqid(), $dto->name, $dto->stateId, $dto->details);
        $product->setImages($dto->images);
        $seller = new Seller($this->user->getId());

        $this->AdvertRepository->save(new Advert(uniqid(),  $seller, $dto->price, $dto->quantity));
    }
}