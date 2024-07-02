<?php

namespace Ondra\App\System\UI\Http\Web\forms;

use Nette\Application\UI\Form;
use Nette\Security\AuthenticationException;
use Nette\Security\User;

class SignInFormFactory
{
     public function __construct(
         public User $user,
        ){
        }
    public function create(): Form
    {
        $form = new Form;
        $form->addText('username', 'Uživatelské jméno')
                ->setRequired('Tak seš kokot?');
        $form->addPassword('password', 'Heslo')
                ->setRequired('Heslo!');
        $form->addSubmit('signIn', 'Přihlásit se');
        $form->onSuccess[] = [$this, 'formSubmitted'];
        return $form;
    }
    public function formSubmitted(Form $form, \stdClass $data): void{
        try{
            $this->user->login($data->username, $data->password, $this);
        }
        catch (AuthenticationException $e) {
            $form->addError($e->getMessage());
            return;
        }
    }
}