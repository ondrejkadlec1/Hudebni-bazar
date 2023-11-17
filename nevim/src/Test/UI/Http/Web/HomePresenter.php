<?php

declare(strict_types=1);

namespace Ondra\App\Test\UI\Http\Web;

use Nette\Application\UI\Presenter;
use Nette\Bridges\ApplicationLatte\DefaultTemplate;
use Ondra\App\System\Application\CQRS;
use Ondra\App\System\Application\CQRSCapable;
use Ondra\App\Test\Application\Query\StatusQuery;

/**
 * @property-read DefaultTemplate $template
 */
final class HomePresenter extends Presenter implements CQRSCapable
{
	use CQRS;

	public function actionDefault(): void
	{
		$status = $this->sendQuery(new StatusQuery(2))->status();

		$this->template->add('status', $status);
	}
}
