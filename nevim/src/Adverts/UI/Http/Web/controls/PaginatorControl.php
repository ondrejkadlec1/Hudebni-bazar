<?php

declare(strict_types=1);

namespace Ondra\App\Adverts\UI\Http\Web\controls;

use Nette\Application\UI\Control;
use Nette\Utils\Paginator;

final class PaginatorControl extends Control
{
	public function __construct(private readonly Paginator $paginator)
	{
	}

	public function render(): void
	{
		$this->template->paginator = $this->paginator;
		$this->template->render(dirname(__DIR__, 1) . "/templates/Shared/paginator.latte");
	}
}
