<?php

declare(strict_types=1);

namespace Ondra\App\Users\UI\Http\Web\forms;

use Nette\Application\UI\Form;
use Nette\Security\Passwords;
use Nette\Security\User;
use Ondra\App\Shared\UI\Http\Web\forms\FormFactory;
use Ondra\App\Users\Application\Command\Messages\ChangePasswordCommandRequest;

class ChangePwdFormFactory extends FormFactory
{
	private User $user;
	private Passwords $passwords;

	/**
	 * @param User $user
	 * @param Passwords $passwords
	 */
	public function __construct(User $user, Passwords $passwords)
	{
		$this->user = $user;
		$this->passwords = $passwords;
	}

	public function create(): Form
	{
		$form = new Form();
		$form->addPassword('oldPassword', 'Staré heslo')
			->setRequired('Zadej staré heslo.');
		$form->addPassword('newPassword', 'Nové heslo')
			->setRequired('Zadej nové heslo.');
		$form->addPassword('newPasswordAgain', 'Heslo znovu')
			->setRequired('Zadej nové heslo znovu.');
		$form->addSubmit('submit', 'Odeslat');
		$form->onSuccess[] = [$this, 'formSubmitted'];
		return $form;
	}
	public function formSubmitted(Form $form, \stdClass $data): void
	{
		if ($data->newPassword !== $data->newPasswordAgain) {
			$form->addError("Hesla se neshodují.");
			return;
		}
		try {
			$this->sendCommand(
				new ChangePasswordCommandRequest($this->user->getId(), $this->passwords->hash(
					$data->newPassword,
				), $data->oldPassword),
			);
		} catch (\Exception $e) {
			if ($e->getPrevious()->getCode() === 1) {
				$form->addError("Špatné heslo");
			}
		}
	}
}
