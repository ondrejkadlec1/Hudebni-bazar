<?php

declare(strict_types=1);

namespace Ondra\App\Adverts\UI\Http\Web\traits;

use Nette\Utils\Paginator;
use Ondra\App\Adverts\Application\Query\Messages\Request\GetAdvertsCountQuery;
use Ondra\App\Adverts\UI\Http\Web\controls\PaginatorControl;

trait Paginated
{
	private Paginator $paginator;

	public function createComponentPaginator(): PaginatorControl
	{
		$this->paginator->setItemCount($this->sendQuery(new GetAdvertsCountQuery($this->criteria))->count);
		return new PaginatorControl($this->paginator);
	}

    public function injectSetupPaginated(): void
	{
		$this->onStartup[] = function () {
			if ($this->getParameter('page') !== null) {
				$page = (int) $this->getParameter('page');
			} else {
				$page = 1;
			}
			$this->paginator = new Paginator();
			$this->paginator->setItemsPerPage(12);
			$this->paginator->setPage($page);
            $this->criteria->addArray([
                'limit' => $this->paginator->getItemsPerPage(),
                'offset' => $this->paginator->getOffset(),
            ]);
		};
	}
}
