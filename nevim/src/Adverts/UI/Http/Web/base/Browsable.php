<?php

declare(strict_types=1);

namespace Ondra\App\Adverts\UI\Http\Web\base;

use Ondra\App\Adverts\Application\Query\DTOs\SearchCriteria;
use Ondra\App\Adverts\UI\Http\Web\controls\AdvertsListControl;
use Ondra\App\Adverts\UI\Http\Web\controls\AdvertsListFactory;
use Ondra\App\Shared\UI\Http\Web\base\FrontendPresenter;

abstract class Browsable extends FrontendPresenter
{
    protected SearchCriteria $criteria;
    protected AdvertsListFactory $advertsListFactory;

	public function createComponentAdvertsList(): AdvertsListControl
    {
        return $this->advertsListFactory->create($this->criteria);
    }

    public function injectAdvertsListFactory(AdvertsListFactory $factory): void
    {
        $this->advertsListFactory = $factory;
    }

    public function injectCriteria(): void
    {
        $this->criteria = SearchCriteria::fromArray([]);
    }
}
