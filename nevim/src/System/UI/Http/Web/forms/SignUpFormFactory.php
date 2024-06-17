<?php

namespace Ondra\App\System\UI\Http\Web\authentication;

use Nette\Application\UI\Form;
use Nette\Database\Connection;
use Nette\Database\Explorer;
use Nette\Security\AuthenticationException;
use Nette\Security\Passwords;
use Nette\Security\User;
use Nette\Utils\Validators;

class SignUpFormFactory
{
 public function __construct(
     private Connection $connection,
     private Explorer $explorer,
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
                $nameUsed = $this->explorer->table('users')->select('username')->where('username', $data->username)->fetch();
                $emailUsed = $this->explorer->table('users')->select('email')->where('email', $data->email)->fetch();
                if ($nameUsed) {
                    throw new AuthenticationException("Toto uživatelsé jméno už někdo použil.");
                }
                if ($emailUsed) {
                    throw new AuthenticationException("Tento email už někdo použil.");
                }
                if ($data->password != $data->passwordAgain){
                    throw new AuthenticationException("Hesla se neshodují.");
                }
                $this->connection->query("INSERT INTO users(username, password, email) VALUES(?, ?, ?)", $data->username, $this->passwords->hash($data->password), $data->email);
                $this->user->login($data->username, $data->password, $this);
            }
            catch (AuthenticationException $e) {
                $form->addError($e->getMessage());
            }
        }
}