<?php

namespace Ondra\App\Users\UI\Http\Web;

use Nette\Application\UI\Presenter;
use Nette\Security\User;
use Ondra\App\Users\UI\Http\Web\forms\ChangePwdFormFactory;

class ProfilePresenter extends Presenter
{
    public function __construct(private readonly User $user, private readonly ChangePwdFormFactory $factory)
    {
    }

    public function renderDefault() {
        $this->template->data = $this->user->getIdentity()->getData();
    }
    public function createComponentChangePwdForm() {
        $form = $this->factory->create();
        $form->onSuccess[] = function () {
            $this->flashMessage('Heslo bylo změněno');
            $this->redirect('Profile:default');
        };
        return $form;
    }
}