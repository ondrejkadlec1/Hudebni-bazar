<?php

declare(strict_types=1);

namespace Ondra\App\Adverts\Application\Query\Handlers;

use Nette\Application\Responses\FileResponse;
use Nette\Utils\Image;
use Ondra\App\Adverts\Application\Query\Messages\Request\GetItemImageQuery;
use Ondra\App\Adverts\Application\Query\Messages\Response\GetItemImageResponse;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class GetItemImageQueryHandler
{
	public function __invoke(GetItemImageQuery $query): GetItemImageResponse
	{
		$imagePath = $_ENV["ITEM_IMAGES_DIRECTORY"] . $query->name;
		$fileResponse = new FileResponse($imagePath);
		$mimeType = Image::typeToMimeType(Image::detectTypeFromFile($imagePath));
		return new GetItemImageResponse($mimeType, $fileResponse);
	}
}
