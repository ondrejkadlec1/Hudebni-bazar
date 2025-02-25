<?php

declare(strict_types=1);

namespace Ondra\App\Shared\UI\Http\Web\traits;

trait UsersOnly
{
    public function injectRedirectGuest(): void
    {
        $this->onStartup[] = function (): void {
            if ($this->user->isloggedIn()) {
                return;
            }
            $this->flashMessage('Pro tuto akci se musíte přihlásit.');
            $this->redirect(':Users:Sign:in', [
                'backlink' => $this->storeRequest(),
            ]);
        };
    }
}
