<?php

declare(strict_types=1);

namespace Ondra\App\Shared\UI\Http\Web;

use Ondra\App\Shared\Application\BusProvider;

abstract class ControlFactory
{
	protected BusProvider $busProvider;

	/**
	 * @param BusProvider $busProvider
	 */
	public function __construct(BusProvider $busProvider)
	{
		$this->busProvider = $busProvider;
	}
}
