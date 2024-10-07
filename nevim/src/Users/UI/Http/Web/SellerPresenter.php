<?php

declare(strict_types=1);

namespace Ondra\App\Users\UI\Http\Web;

use Ondra\App\Adverts\UI\Http\Web\Browsing;
use Ondra\App\Shared\Application\Exceptions\MissingContentException;
use Ondra\App\Shared\UI\Http\Web\FrontendPresenter;
use Ondra\App\Users\Application\Query\Messages\GetSellerProfileQuery;

final class SellerPresenter extends FrontendPresenter
{
	use Browsing;
	public function renderDefault(string $sellerId): void
	{
		try {
			$this->template->profile = $this->sendQuery(new GetSellerProfileQuery($sellerId))->dto;
		} catch (\Exception $e) {
			if ($e->getPrevious() instanceof MissingContentException) {
				$this->error('Hledaný prodejce neexistuje.', 404);
			}
		}
	}
}
