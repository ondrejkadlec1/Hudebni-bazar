<?php

namespace Ondra\App\Users\UI\Http\Web\forms;

use Nette\Application\UI\Form;
use Nette\Security\AuthenticationException;
use Nette\Security\Passwords;
use Nette\Security\User;
use Ondra\App\Shared\Application\BusProvider;
use Ondra\App\Users\Application\Command\ChangePasswordCommandRequest;
use Ondra\App\Users\Application\security\UserAuthenticator;
use Ondra\App\Shared\UI\Http\Web\forms\FormFactory;

class ChangePwdFormFactory extends FormFactory
{
    public function __construct(
        private User $user,
        private Passwords $passwords,
        private UserAuthenticator $authenticator,
        protected BusProvider $busProvider
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
            if ($data->newPassword != $data->newPasswordAgain) {
                throw new AuthenticationException("Hesla se neshodují.");
            }
            $this->authenticator->reauthenticate($id, $data->newPassword);
        }
        catch (AuthenticationException $exception) {
            $form->addError($exception->getMessage());
        }
        try {
            $this->sendQuery(new ChangePasswordCommandRequest($id, $this->passwords->hash($data->newPassword)));
        }
        catch (\Exception $exception) {
            $form->addError($exception->getMessage());
        }
    }
}