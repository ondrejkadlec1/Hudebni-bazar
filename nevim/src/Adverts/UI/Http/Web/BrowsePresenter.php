<?php

declare(strict_types=1);

namespace Ondra\App\Adverts\UI\Http\Web;

use Ondra\App\Adverts\Application\Query\Messages\Request\GetItemImageQuery;
use Ondra\App\Adverts\Application\Query\Messages\Request\GetListNameQuery;
use Ondra\App\Shared\Application\Exceptions\MissingContentException;
use Ondra\App\Shared\UI\Http\Web\FrontendPresenter;

class BrowsePresenter extends FrontendPresenter
{
	use Paginated;
	private ?int $categoryId = null;
	private ?int $subcategoryId = null;
	private ?int $subsubcategoryId = null;
	public function renderCategory(int $categoryId): void
	{
		$this->categoryId = $categoryId;
		try {
			$this->template->category = $this->sendQuery(
				new GetListNameQuery($this->categoryId, GetListNameQuery::$isCategory),
			)->name;
		} catch (\Exception $e) {
			if ($e->getPrevious() instanceof MissingContentException) {
				$this->error('Hledaná kategorie neexistuje', 404);
			}
		}
	}
	public function renderDefault(?string $search = null): void
	{
	}

	public function renderSubcategory(int $subcategoryId): void
	{
		$this->subcategoryId = $subcategoryId;
		try {
			$this->template->subcategory = $this->sendQuery(
				new GetListNameQuery($this->subcategoryId, GetListNameQuery::$isSubcategory),
			)->name;
		} catch (\Exception $e) {
			if ($e->getPrevious() instanceof MissingContentException) {
				$this->error('Hledaná podkategorie neexistuje', 404);
			}
		}
	}
	public function renderSubsubcategory(int $subsubcategoryId): void
	{
		$this->subsubcategoryId = $subsubcategoryId;
		try {
			$this->template->subsubcategory = $this->sendQuery(
				new GetListNameQuery($this->subsubcategoryId, GetListNameQuery::$isSubsubcategory),
			)->name;
		} catch (\Exception $e) {
			if ($e->getPrevious() instanceof MissingContentException) {
				$this->error('Hledaná podpodkategorie neexistuje', 404);
			}
		}
	}

	public function actionImage(string $imageName): void
	{
		$response = $this->sendQuery(new GetItemImageQuery($imageName));
		$this->getHttpResponse()->setHeader('Content-Type', $response->mimeType);
		$this->sendResponse($response->fileResponse);
	}
}
