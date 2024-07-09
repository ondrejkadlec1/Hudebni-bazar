<?php

namespace Ondra\App\Shared\UI\Http\Web;

use Nette\Application\UI\Presenter;
use Ondra\App\Shared\Application\CQRS;
use Ondra\App\Shared\Application\CQRSCapable;

class FrontendPresenter extends Presenter implements CQRSCapable
{
    use CQRS;
}