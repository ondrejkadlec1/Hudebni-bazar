<?php

declare(strict_types=1);

namespace Ondra\App\Adverts\UI\Http\Web;

use Nette\Utils\Paginator;
use Ondra\App\Adverts\Application\Query\Messages\Request\GetAdvertsCountQuery;

trait Paginated
{
	use Browsing;
	private Paginator $paginator;
	public function injectSetupPaginated(): void
	{
		$this->onStartup[] = function () {
			if ($this->getParameter('page') !== null) {
				$page = (int) $this->getParameter('page');
			} else {
				$page = 1;
			}
			$this->paginator = new Paginator();
			$this->paginator->setItemsPerPage((int) $_ENV["ITEMS_PER_PAGE"]);
			$this->paginator->setPage($page);
			$this->offset = $this->paginator->getOffset();
			$this->limit = $this->paginator->getItemsPerPage();
		};
	}

	public function createComponentPaginator(): PaginatorControl
	{
		$this->paginator->setItemCount($this->sendQuery(new GetAdvertsCountQuery($this->criteria))->count);
		return new PaginatorControl($this->paginator);
	}
}
