<?php

declare(strict_types=1);

namespace Ondra\App\Users\UI\Http\Web\forms;

use Nette\Application\UI\Form;
use Nette\Security\AuthenticationException;
use Nette\Security\User;

class SignInFormFactory
{
	public User $user;
	/**
	 * @param User $user
	 */
	public function __construct(User $user)
	{
		$this->user = $user;
	}

	public function create(): Form
	{
		$form = new Form();
		$form->addText('username', 'Uživatelské jméno')
				->setRequired('Tak seš kokot?');
		$form->addPassword('password', 'Heslo')
				->setRequired('Heslo!');
		$form->addSubmit('signIn', 'Přihlásit se');
		$form->onSuccess[] = [$this, 'formSubmitted'];
		return $form;
	}
	public function formSubmitted(Form $form, \stdClass $data): void
	{
		try {
			$this->user->login($data->username, $data->password);
		} catch (AuthenticationException $e) {
			if ($e->getCode() === 0) {
				$form->addError('Takový uživatel tady ani není.');
			}
			if ($e->getCode() === 1) {
				$form->addError('Špatné heslo.');
			}
		}
	}
}
