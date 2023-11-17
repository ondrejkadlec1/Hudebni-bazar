<?php

declare(strict_types=1);

namespace Ondra\App\System\UI\Http\Web;

use Nette\Application\BadRequestException;
use Nette\Application\Helpers;
use Nette\Application\IPresenter;
use Nette\Application\Request;
use Nette\Application\Response;
use Nette\Application\Responses\CallbackResponse;
use Nette\Application\Responses\ForwardResponse;
use Nette\Http;
use Nette\Http\IRequest;
use Nette\Http\IResponse;
use Tracy\ILogger;

/**
 * Handles uncaught exceptions and errors, and logs them.
 */
final class ErrorPresenter implements IPresenter
{
	public function __construct(
		private readonly ILogger $logger,
	) {
	}


	public function run(Request $request): Response
	{
		$exception = $request->getParameter('exception');

		// If the exception is a 4xx HTTP error, forward to the Error4xxPresenter
		if ($exception instanceof BadRequestException) {
			[$module, , $sep] = Helpers::splitName($request->getPresenterName());
			return new ForwardResponse($request->setPresenterName($module . $sep . 'Error4xx'));
		}

		// Log the exception and display a generic error message to the user
		$this->logger->log($exception, ILogger::EXCEPTION);
		return new CallbackResponse(static function (IRequest $httpRequest, IResponse $httpResponse): void {
			if (preg_match('#^text/html(?:;|$)#', (string) $httpResponse->getHeader('Content-Type'))) {
				require __DIR__ . '/templates/Error/500.phtml';
			}
		});
	}
}
