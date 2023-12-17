<?php

namespace Ondra\App\System\Application\Model;

use Nette\Application\UI\Form;
use Nette\Database\Connection;
use Nette\Utils\Validators;
use Nette\Security\Passwords;
use Nette\Security\User;

class SignUpFormFactory
{
 public function __construct(
     private Connection $connection,
     private Passwords $passwords,
     private User $user
    ){
    }
    public function create(): Form
    {
        $form = new Form;
        $form->addText('username', 'Uživatelské jméno')
                ->setRequired('Jméno!');
        $form->addEmail('email', 'email')
                ->setRequired('A dělej!');
        $form->addPassword('password', 'Heslo')
                ->setRequired('Heslo!');
        $form->addPassword('passwordAgain', 'Heslo znovu')
            ->setRequired('Heslo!');
        $form->addSubmit('signUp', 'Zaregistrovat se');
        $form->onSuccess[] = [$this, 'formSubmitted'];
        return $form;
    }
        public function formSubmitted(Form $form, \stdClass $data): void{
            Validators::assert($data->email, 'email');
            try {
                $this->connection->query("INSERT INTO users(username, password, email) VALUES(?, ?, ?)", $data->username, $this->passwords->hash($data->password), $data->email);
                $this->user->login($data->username, $data->password, $this);
            }
            catch (Nette\Database\UniqueConstraintViolationException $e) {
                $form->addError(print_r($e));
            }
        }
}