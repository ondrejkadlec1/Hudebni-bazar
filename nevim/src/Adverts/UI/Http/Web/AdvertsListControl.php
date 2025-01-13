<?php

declare(strict_types=1);

namespace Ondra\App\Adverts\UI\Http\Web;

use Nette\Application\UI\Control;
use Ondra\App\Adverts\Application\Query\DTOs\SearchCriteria;
use Ondra\App\Adverts\Application\Query\Messages\Request\GetAdvertsQuery;
use Ondra\App\Shared\Application\BusProvider;

final class AdvertsListControl extends Control
{
	public function __construct(
		private readonly BusProvider $busProvider,
		private readonly SearchCriteria $criteria,
	) {
	}
	public function render(): void
	{
		$response = $this->busProvider->sendQuery(new GetAdvertsQuery($this->criteria));
		$this->template->adverts = $response->dtos;
		$this->template->render(__DIR__ . "/templates/Shared/advertsList.latte");
	}
}
