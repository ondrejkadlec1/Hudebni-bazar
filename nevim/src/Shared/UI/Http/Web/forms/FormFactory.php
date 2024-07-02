<?php

namespace Ondra\App\System\UI\Http\Web\forms;

use Ondra\App\System\Application\CQRS;
use Ondra\App\System\Application\CQRSCapable;

abstract class FormFactory implements CQRSCapable
{
    use CQRS;
}