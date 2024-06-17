<?php

namespace Ondra\App\System\UI\Http\Web\authentication;

use Nette\Application\UI\Form;
use Nette\Database\Connection;
use Nette\Security\AuthenticationException;
use Nette\Security\Passwords;
use Nette\Security\User;

class ChangePwdFormFactory
{
    public function __construct(
        private Connection $connection,
        private User $user,
        private Passwords $passwords
    ){
    }
    public function create(): Form {
        $form = new Form;
        $form->addPassword('oldPassword', 'Staré heslo')
            ->setRequired('Zadej staré heslo.');
        $form->addPassword('newPassword', 'Nové heslo')
            ->setRequired('Zadej nové heslo.');
        $form->addPassword('newPasswordAgain', 'Heslo znovu')
            ->setRequired('Zadej nové heslo znovu.');
        $form->addSubmit('submit','Odeslat');
        $form->onSuccess[] = [$this, 'formSubmitted'];
        return $form;
    }
    public function formSubmitted(Form $form, \stdClass $data) {
        try {
            $id = $this->user->getId();

            $row = $this->connection->fetch('SELECT password FROM users WHERE id = ?', $id);
            if (!$this->passwords->verify($data->oldPassword, $row->password)) {
                throw new AuthenticationException("Špatné heslo");
            }
            if ($data->newPassword != $data->newPasswordAgain) {
                throw new AuthenticationException("Hesla se neshodují.");
            }
            $this->connection->query("UPDATE users SET password = ? WHERE id = ?", $this->passwords->hash($data->newPassword), $id);
        }
        catch (AuthenticationException $e) {
            $form->addError($e->getMessage());
        }
    }
}