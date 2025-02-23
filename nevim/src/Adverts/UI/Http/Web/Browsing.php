<?php

declare(strict_types=1);

namespace Ondra\App\Adverts\UI\Http\Web;

use Ondra\App\Adverts\Application\Query\DTOs\SearchCriteria;
use Ondra\App\Adverts\UI\Http\Web\forms\FilterFormFactory;

trait Browsing
{
	private SearchCriteria $criteria;

	public function __construct(private readonly FilterFormFactory $filterFormFactory, private readonly AdvertsListFactory $advertsListFactory)
	{
        $this->criteria = SearchCriteria::fromArray([]);
	}
	public function createComponentAdvertsList(): AdvertsListControl
	{
		return $this->advertsListFactory->create($this->criteria);
	}
}
