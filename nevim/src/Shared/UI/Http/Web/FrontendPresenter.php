<?php

namespace Ondra\App\System\UI\Http\Web;

use Nette\Application\UI\Presenter;
use Ondra\App\System\Application\CQRS;
use Ondra\App\System\Application\CQRSCapable;

class FrontendPresenter extends Presenter implements CQRSCapable
{
    use CQRS;
}