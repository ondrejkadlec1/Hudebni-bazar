<?php

declare(strict_types=1);

namespace Ondra\App\Adverts\UI\Http\Web;

use Exception;
use Ondra\App\Adverts\Application\Query\Messages\Request\GetItemImageQuery;
use Ondra\App\Adverts\Application\Query\Messages\Request\GetListNameQuery;
use Ondra\App\Shared\Application\Exceptions\MissingContentException;
use Ondra\App\Shared\UI\Http\Web\FrontendPresenter;

final class BrowsePresenter extends FrontendPresenter
{
	use Paginated;
	public function actionImage(string $imageName): void
	{
		$response = $this->sendQuery(new GetItemImageQuery($imageName));
		$this->getHttpResponse()->setHeader('Content-Type', $response->mimeType);
		$this->sendResponse($response->fileResponse);
	}

	public function renderCategory(int $categoryId): void
	{
		try {
			$this->template->category = $this->sendQuery(
				new GetListNameQuery($categoryId),
			)->name;
		}
        catch (Exception $e) {
			if ($e->getPrevious() instanceof MissingContentException) {
				$this->error('Hledaná kategorie neexistuje', 404);
			}
		}
	}

	public function renderDefault(?string $search = null): void
	{
	}
}
