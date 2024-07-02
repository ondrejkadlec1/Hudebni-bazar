<?php

namespace Ondra\App\System\UI\Http\Web;

use Nette\Application\UI\Presenter;
use Nette\Security\User;
use Ondra\App\System\UI\Http\Web\forms\ChangePwdFormFactory;

class ProfilePresenter extends Presenter
{
    public function __construct(private readonly User $user, private ChangePwdFormFactory $factory)
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