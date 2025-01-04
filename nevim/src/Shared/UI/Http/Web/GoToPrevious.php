<?php

declare(strict_types=1);

namespace Ondra\App\Shared\UI\Http\Web;

use Nette\Application\Attributes\Persistent;

trait GoToPrevious
{
	#[Persistent]
	public $backlink;
	public function injectGetBacklink(): void
	{
		$this->onStartup[] = function (): void {
			$this->backlink = $this->getParameter('backlink');
		};
	}
	public function goToPrevious(): void
	{
		if (isset($this->backlink)) {
			$this->restoreRequest($this->backlink);
		} else {
			$this->redirect(':Adverts:Home:default');
		}
	}
}
