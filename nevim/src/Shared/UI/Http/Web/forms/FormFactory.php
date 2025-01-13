<?php

declare(strict_types=1);

namespace Ondra\App\Shared\UI\Http\Web\forms;

use Ondra\App\Shared\Application\CQRS;
use Ondra\App\Shared\Application\CQRSCapable;

abstract class FormFactory implements CQRSCapable
{
	use CQRS;
}
