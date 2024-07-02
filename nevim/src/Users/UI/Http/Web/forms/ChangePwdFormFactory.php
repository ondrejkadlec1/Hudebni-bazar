<?php

namespace Ondra\App\System\UI\Http\Web\forms;

use Nette\Application\UI\Form;
use Nette\Security\AuthenticationException;
use Nette\Security\Passwords;
use Nette\Security\User;
use Ondra\App\System\Application\BusProvider;
use Ondra\App\System\Application\Command\ChangePasswordCommandRequest;
use Ondra\App\System\Application\security\UserAuthenticator;

class ChangePwdFormFactory
{
    public function __construct(
        private User $user,
        private Passwords $passwords,
        private UserAuthenticator $authenticator,
        private BusProvider $busProvider
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
            $this->busProvider->sendCommand(new ChangePasswordCommandRequest($id, $this->passwords->hash($data->newPassword)));
        }
        catch (\Exception $exception) {
            $form->addError($exception->getPrevious()->getMessage());
        }
    }
}