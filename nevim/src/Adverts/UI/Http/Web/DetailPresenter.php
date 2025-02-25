<?php

declare(strict_types=1);

namespace Ondra\App\Adverts\UI\Http\Web;

use Exception;
use Ondra\App\Adverts\Application\Query\Messages\Request\GetAdvertQuery;
use Ondra\App\Shared\Application\Exceptions\MissingContentException;
use Ondra\App\Shared\UI\Http\Web\base\FrontendPresenter;

final class DetailPresenter extends FrontendPresenter
{
	public function renderShow(string $id): void
	{
		try {
			$queryResponse = $this->sendQuery(new GetAdvertQuery($id));
		} catch (Exception $e) {
			if ($e->getPrevious() instanceof MissingContentException) {
                $this->error('Hledaná nabídka neexistuje', 404);
            }
			throw $e;
		}
		$this->template->advert = $queryResponse->dto;
		$otherImages = $queryResponse->dto->imageNames;
		if ($otherImages === []) {
			unset($otherImages[0]);
		}
		$this->template->imageNames = $otherImages;
		$this->template->userId = $this->user->getId();
	}
}
