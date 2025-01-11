<?php

declare(strict_types=1);

namespace Ondra\App\Adverts\Application\Command\Handlers;

use Nette\Http\FileUpload;
use Nette\Security\User;
use Ondra\App\Adverts\Application\Command\Messages\CreateAdvertCommandRequest;
use Ondra\App\Adverts\Application\IAdvertWriteRepository;
use Ondra\App\Adverts\Domain\Advert;
use Ondra\App\Adverts\Domain\Item;
use Ondra\App\Adverts\Domain\ItemImage;
use Ondra\App\Adverts\Domain\Seller;
use Ondra\App\Shared\Application\Autowired;
use Ondra\App\Shared\Application\Exceptions\PermissionException;
use Ondra\App\Shared\Application\Roles\RoleAssigner;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class CreateAdvertCommandHandler implements Autowired
{
	public function __construct(
		private readonly IAdvertWriteRepository $advertRepository,
		private readonly User $user,
		private readonly RoleAssigner $roleAssigner,
	) {}

	public function __invoke(CreateAdvertCommandRequest $command): void
	{
		if (! $this->roleAssigner->isAllowed('advert', 'create')) {
			throw new PermissionException('user is not signed in', 0);
		}
		$dto = $command->dto;
        $id = uniqid();
		$itemImages = [];
		foreach ($dto->images as $imageId => $file) {
            $itemImages[] = new ItemImage($imageId, $file);
		}
        bdump($itemImages);
		$item = new Item(
            $id,
			$dto->name,
			$dto->details,
			$dto->stateId,
			$itemImages,
			$dto->subsubcategoryId,
			$dto->brand,
		);

		$seller = new Seller($this->user->getId());
		$this->advertRepository->save(new Advert(uniqid(), $item, $seller, $dto->price, $dto->quantity));
	}
}
