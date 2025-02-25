<?php

declare(strict_types=1);

namespace Ondra\App\Adverts\UI\Http\Web;

use Exception;
use Ondra\App\Adverts\Application\Query\Messages\Request\GetItemImageQuery;
use Ondra\App\Adverts\Application\Query\Messages\Request\GetListNameQuery;
use Ondra\App\Adverts\UI\Http\Web\base\Browsable;
use Ondra\App\Adverts\UI\Http\Web\traits\Filtered;
use Ondra\App\Adverts\UI\Http\Web\traits\Paginated;
use Ondra\App\Shared\Application\Exceptions\MissingContentException;

final class BrowsePresenter extends Browsable
{
	use Filtered;
    use Paginated;
	public function actionImage(string $imageName): void
	{
		$response = $this->sendQuery(new GetItemImageQuery($imageName));
		$this->getHttpResponse()->setHeader('Content-Type', $response->mimeType);
		$this->sendResponse($response->fileResponse);
	}

	public function renderCategory(int $categoryId): void
	{
        $this->criteria->addArray(["categoryId" => $categoryId]);
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
        $search = $this->getParameter('search');
        if ($search !== null){
            $this->criteria->addArray(['expression' => $search]);
        }
	}
}
