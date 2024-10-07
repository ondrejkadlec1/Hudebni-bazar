<?php

declare(strict_types=1);

namespace Ondra\App\Adverts\UI\Http\Web;

use Ondra\App\Adverts\Application\Query\Messages\Request\GetAdvertQuery;
use Ondra\App\Adverts\Application\Query\Messages\Request\GetItemImageQuery;
use Ondra\App\Shared\Application\Exceptions\MissingContentException;
use Ondra\App\Shared\UI\Http\Web\FrontendPresenter;

class DetailPresenter extends FrontendPresenter
{
	public function renderShow(string $id): void
	{
		try {
			$queryResponse = $this->sendQuery(new GetAdvertQuery($id));
		} catch (\Exception $e) {
			if ($e->getPrevious() instanceof MissingContentException) {
				$this->error('Hledaná nabídka neexistuje', 404);
			}
            else throw $e;
		}
		$this->template->advert = $queryResponse->dto;
		$this->template->imageNames = $queryResponse->dto->imageNames;
		$this->template->userId = $this->user->getId();
	}
	public function actionImages(string $imageName)
	{
		$response = $this->sendQuery(new GetItemImageQuery($imageName));
		$this->getHttpResponse()->setHeader('Content-Type', $response->mimeType);
		$this->sendResponse($response->fileResponse);
	}
}
