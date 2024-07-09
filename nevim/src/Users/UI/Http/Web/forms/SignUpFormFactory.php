<?php

namespace Ondra\App\Users\UI\Http\Web\forms;

use Nette\Application\UI\Form;
use Nette\Security\AuthenticationException;
use Nette\Security\Passwords;
use Nette\Utils\Validators;
use Ondra\App\Shared\Application\BusProvider;
use Ondra\App\Users\Application\Command\CreateUserCommandRequest;
use Nette\Security\User;
use Ondra\App\Shared\UI\Http\Web\forms\FormFactory;
class SignUpFormFactory extends FormFactory
{

    public function __construct(protected BusProvider $busProvider, private Passwords $passwords, private User $user)
    {
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
                if ($data->password != $data->passwordAgain) {
                    throw new AuthenticationException("Hesla se neshodují.");
                }
            }
            catch (AuthenticationException $exception){
                $form->addError($exception->getMessage());
            }
            try {
                $this->sendCommand(new CreateUserCommandRequest($data->username, $this->passwords->hash($data->password), $data->email));
                $this->user->login($data->username, $data->password);
            }
            catch (\Exception $exception) {
                $form->addError($exception->getPrevious()->getMessage());
            }
        }
}