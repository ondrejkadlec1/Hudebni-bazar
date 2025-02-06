<?php

declare(strict_types=1);

namespace Ondra\App\Users\UI\Http\Web\forms;

use stdClass;
use Exception;
use Nette\Application\UI\Form;
use Nette\Security\Passwords;
use Nette\Security\User;
use Nette\Utils\Validators;
use Ondra\App\Shared\Application\Exceptions\ValidationException;
use Ondra\App\Shared\UI\Http\Web\forms\FormFactory;
use Ondra\App\Users\Application\Command\Messages\CreateUserCommandRequest;

final class SignUpFormFactory extends FormFactory
{
	public function __construct(private readonly Passwords $passwords, private readonly User $user)
	{
	}


	public function create(): Form
	{
		$form = new Form();
		$form->addText('username', 'Uživatelské jméno')
				->setRequired('Zadejte uživatelské jméno.');
		$form->addEmail('email', 'email')
				->setRequired('Zadejte email');
		$form->addPassword('password', 'Heslo')
				->setRequired('Zadejte heslo');
		$form->addPassword('passwordAgain', 'Heslo znovu')
			->setRequired('Zopakujte heslo');
		$form->addSubmit('signUp', 'Zaregistrovat se');
		$form->onSuccess[] = $this->formSubmitted(...);
		return $form;
	}
	public function formSubmitted(Form $form, stdClass $data): void
	{
		try {
			Validators::assert($data->email, 'email');
		} catch (Exception $e) {
			$form->addError('Neplatný email');
		}
		if ($data->password !== $data->passwordAgain) {
			$form->addError('Hesla se neshodují');
			return;
		}
		try {
			$this->sendCommand(
				new CreateUserCommandRequest($data->username, $data->email, $this->passwords->hash($data->password)),
			);
			$this->user->login($data->username, $data->password);
		}
        catch (Exception $e) {
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
