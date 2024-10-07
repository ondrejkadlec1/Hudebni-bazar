<?php

declare(strict_types=1);

namespace Ondra\App\Users\UI\Http\Web\forms;

use Nette\Application\UI\Form;
use Nette\Security\Passwords;
use Nette\Security\User;
use Nette\Utils\Validators;
use Ondra\App\Shared\Application\Exceptions\ValidationException;
use Ondra\App\Shared\UI\Http\Web\forms\FormFactory;
use Ondra\App\Users\Application\Command\Messages\CreateUserCommandRequest;

class SignUpFormFactory extends FormFactory
{
	private Passwords $passwords;
	private User $user;

	/**
	 * @param Passwords $passwords
	 * @param User $user
	 */
	public function __construct(Passwords $passwords, User $user)
	{
		$this->passwords = $passwords;
		$this->user = $user;
	}


	public function create(): Form
	{
		$form = new Form();
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
	public function formSubmitted(Form $form, \stdClass $data): void
	{
		Validators::assert($data->email, 'email');
		if ($data->password !== $data->passwordAgain) {
			$form->addError('Hesla se neshodují');
			return;
		}
		try {
			$this->sendCommand(
				new CreateUserCommandRequest($data->username, $data->email, $this->passwords->hash($data->password)),
			);
			$this->user->login($data->username, $data->password);
		} catch (\Exception $e) {
            $previous = $e->getPrevious();
            if ($previous instanceof ValidationException) {
                if ($e->getPrevious()->getCode() === 0) {
                    $form->addError("Toto uživatelsé jméno už někdo použil.");
                }
                if ($e->getPrevious()->getCode() === 1) {
                    $form->addError("Tento email už někdo použil.");
                }
            }
		}
	}
}
