<?php

declare(strict_types=1);

namespace Ondra\App\System\UI\Http\Web;

use Nette\Application\BadRequestException;
use Nette\Application\Request;
use Nette\Application\UI\Presenter;
use Nette\Bridges\ApplicationLatte\DefaultTemplate;

/**
 * @property-read DefaultTemplate $template
 */
final class Error4xxPresenter extends Presenter
{
	public function renderDefault(BadRequestException $exception): void
	{
		// renders the appropriate error template based on the HTTP status code
		$code = $exception->getCode();
		$file = is_file($file = __DIR__ . "/templates/Error/$code.latte")
			? $file
			: __DIR__ . '/templates/Error/4xx.latte';
		$this->template->add('httpCode', $code);
		$this->template->setFile($file);
	}

	protected function checkHttpMethod(): void
	{
		// allow access via all HTTP methods and ensure the request is a forward (internal redirect)
		if (! $this->getRequest()?->isMethod(Request::FORWARD)) {
			$this->error();
		}
	}
}
