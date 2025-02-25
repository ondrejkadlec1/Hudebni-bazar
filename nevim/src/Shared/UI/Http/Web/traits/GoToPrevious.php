<?php

declare(strict_types=1);

namespace Ondra\App\Shared\UI\Http\Web\traits;

use Nette\Application\Attributes\Persistent;

trait GoToPrevious
{
    #[Persistent]
    public $backlink;

    public function goToPrevious(): void
    {
        if ($this->backlink !== null) {
            $this->restoreRequest($this->backlink);
            return;
        }
        $this->redirect(':Adverts:Home:default');
    }

    public function injectGetBacklink(): void
    {
        $this->onStartup[] = function (): void {
            $this->backlink = $this->getParameter('backlink');
        };
    }
}
