<?php

declare(strict_types=1);

namespace Ondra\App\Users\UI\Http\Web\forms;

use stdClass;
use Nette\Application\UI\Form;
use Nette\Security\AuthenticationException;
use Nette\Security\User;

final class SignInFormFactory
{
	public function __construct(private readonly User $user)
	{
	}

	public function create(): Form
	{
		$form = new Form();
		$form->addText('username', 'Uživatelské jméno')
				->setRequired('Zadejte uživatelské jméno');
		$form->addPassword('password', 'Heslo')
				->setRequired('Zadejte heslo');
		$form->addSubmit('signIn', 'Přihlásit se');
		$form->onSuccess[] = $this->formSubmitted(...);
		return $form;
	}
	public function formSubmitted(Form $form, stdClass $data): void
	{
		try {
			$this->user->login($data->username, $data->password);
		} catch (AuthenticationException $e) {
			if ($e->getCode() === 0) {
				$form->addError('Takový uživatel tady ani není.');
			}
			if ($e->getCode() === 1) {
				$form->addError('Špatné uživatelské jméno nebo heslo.');
			}
		}
	}
}
