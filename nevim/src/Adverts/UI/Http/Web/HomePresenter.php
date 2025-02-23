<?php

declare(strict_types=1);

namespace Ondra\App\Adverts\UI\Http\Web;

use Ondra\App\Adverts\Application\Query\DTOs\SearchCriteria;
use Ondra\App\Adverts\Application\Query\Messages\Request\GetListNameQuery;
use Ondra\App\Shared\UI\Http\Web\FrontendPresenter;
use Ondra\App\Users\Application\Query\Messages\GetSellerNameQuery;

final class HomePresenter extends FrontendPresenter
{
	private int $recCategoryId;
	private string $recSellerId;
	public function __construct(private readonly AdvertsListFactory $factory)
	{
		# TODO: implement recommendations
		$this->recSellerId = '668682c5b1185';
		$this->recCategoryId = 38;
	}

	public function createComponentAdvertsListCategory(): AdvertsListControl
	{
		return $this->factory->create(SearchCriteria::fromArray(['limit' => 3, 'categoryId' => $this->recCategoryId]));
	}

	public function createComponentAdvertsListNewest(): AdvertsListControl
	{
		return $this->factory->create(SearchCriteria::fromArray(['limit' => 3]));
	}

	public function createComponentAdvertsListSeller(): AdvertsListControl
	{
		return $this->factory->create(SearchCriteria::fromArray(['limit' => 3, 'sellerId' => $this->recSellerId]));
	}

	public function createComponentAdvertsListStateNew(): AdvertsListControl
	{
		return $this->factory->create(SearchCriteria::fromArray(['limit' => 3, 'stateIds' => [1]]));
	}

	public function renderDefault(): void
	{
		$this->template->recCategoryId = $this->recCategoryId;
		$this->template->recSellerId = $this->recSellerId;
		$this->template->recCategoryName = $this->sendQuery(new GetListNameQuery($this->recCategoryId))->name;
		$this->template->recSellerName = $this->sendQuery(new GetSellerNameQuery($this->recSellerId))->name;
	}
}
