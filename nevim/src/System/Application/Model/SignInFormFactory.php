<?php

namespace Ondra\App\System\Application\Model;

use Nette\Application\UI\Form;
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
        catch (Nette\Security\AuthenticationException $e) {
            $form->addError(print_r($e));
            return;
        }
    }
}