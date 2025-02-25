<?php

declare(strict_types=1);

namespace Ondra\App\Shared\UI\Http\Web\base;

use Ondra\App\Shared\Application\BusProvider;

abstract class ControlFactory
{
	public function __construct(protected BusProvider $busProvider)
 {
 }
}
