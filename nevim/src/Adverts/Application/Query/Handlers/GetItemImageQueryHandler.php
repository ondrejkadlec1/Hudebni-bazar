<?php

declare(strict_types=1);

namespace Ondra\App\Adverts\Application\Query\Handlers;

use Nette\Application\Responses\FileResponse;
use Nette\Utils\Image;
use Ondra\App\Adverts\Application\Query\Messages\Request\GetItemImageQuery;
use Ondra\App\Adverts\Application\Query\Messages\Response\GetItemImageResponse;
use Ondra\App\ApplicationConfiguration;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class GetItemImageQueryHandler
{

    public function __construct(private readonly ApplicationConfiguration $configuration)
    {
    }

    public function __invoke(GetItemImageQuery $query): GetItemImageResponse
   	{
   		$imagePath = sprintf("%s%s", $this->configuration->get()['itemImagesDirectory'], $query->name);
   		$fileResponse = new FileResponse($imagePath);
   		$mimeType = Image::typeToMimeType(Image::detectTypeFromFile($imagePath));
   		return new GetItemImageResponse($mimeType, $fileResponse);
   	}
}
