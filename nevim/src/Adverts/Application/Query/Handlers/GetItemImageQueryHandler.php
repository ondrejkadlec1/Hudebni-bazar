<?php

namespace Ondra\App\Adverts\Application\Query;
use Nette\Application\Responses\FileResponse;
use Nette\Utils\Image;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class GetItemImageQueryHandler
{
    public function __invoke(GetItemImageQuery $query): GetItemImageResponse
    {
        $imagePath = $_ENV["ITEM_IMAGES_DIRECTORY"] . $query->getName();
        $fileResponse = new FileResponse($imagePath);
        $mimeType = Image::typeToMimeType(Image::detectTypeFromFile($imagePath));
        return new GetItemImageResponse($mimeType, $fileResponse);
    }

}