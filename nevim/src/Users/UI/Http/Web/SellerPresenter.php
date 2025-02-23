<?php

declare(strict_types=1);

namespace Ondra\App\Users\UI\Http\Web;

use Exception;
use Ondra\App\Adverts\UI\Http\Web\Filtered;
use Ondra\App\Adverts\UI\Http\Web\Paginated;
use Ondra\App\Shared\Application\Exceptions\MissingContentException;
use Ondra\App\Shared\UI\Http\Web\FrontendPresenter;
use Ondra\App\Users\Application\Query\Messages\GetSellerProfileQuery;

final class SellerPresenter extends FrontendPresenter
{
	use Filtered;
	public function renderDefault(string $sellerId): void
	{
        $this->criteria->addArray(['sellerId' => $sellerId]);
		try {
			$result = $this->sendQuery(new GetSellerProfileQuery($sellerId))->dto;
			$this->template->profile = $result->profile;
			$this->template->description = $result->description;
		}
        catch (Exception $e) {
			if ($e->getPrevious() instanceof MissingContentException) {
				$this->error('Hledaný prodejce neexistuje.', 404);
			}
		}
	}
}
