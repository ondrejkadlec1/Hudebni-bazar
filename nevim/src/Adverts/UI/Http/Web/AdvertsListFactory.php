<?php

declare(strict_types=1);

namespace Ondra\App\Adverts\UI\Http\Web;

use Ondra\App\Adverts\Application\Query\DTOs\SearchCriteria;
use Ondra\App\Shared\UI\Http\Web\ControlFactory;

final class AdvertsListFactory extends ControlFactory
{
	public function create(SearchCriteria $criteria): AdvertsListControl
	{
		return new AdvertsListControl($this->busProvider, $criteria);
	}
}
