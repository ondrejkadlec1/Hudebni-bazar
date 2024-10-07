<?php

declare(strict_types=1);

namespace Ondra\App\Adverts\UI\Http\Web;

use Nette\Application\UI\Control;
use Nette\Utils\Paginator;

class PaginatorControl extends Control
{
	public function __construct(private readonly Paginator $paginator)
	{
	}

	public function render(): void
	{
		$this->template->paginator = $this->paginator;
		$this->template->render(__DIR__ . "/templates/Shared/paginator.latte");
	}
}
