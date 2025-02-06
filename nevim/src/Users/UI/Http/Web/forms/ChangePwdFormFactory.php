<?php

declare(strict_types=1);

namespace Ondra\App\Users\UI\Http\Web\forms;

use stdClass;
use Exception;
use Nette\Application\UI\Form;
use Nette\Security\Passwords;
use Nette\Security\User;
use Ondra\App\Shared\UI\Http\Web\forms\FormFactory;
use Ondra\App\Users\Application\Command\Messages\ChangePasswordCommandRequest;

final class ChangePwdFormFactory extends FormFactory
{
	public function __construct(private readonly User $user, private readonly Passwords $passwords)
	{
	}

	public function create(): Form
	{
		$form = new Form();
		$form->addPassword('oldPassword', 'Staré heslo')
			->setRequired('Zadejte staré heslo.');
		$form->addPassword('newPassword', 'Nové heslo')
			->setRequired('Zadejte nové heslo.');
		$form->addPassword('newPasswordAgain', 'Heslo znovu')
			->setRequired('Zopakujte nové heslo.');
		$form->addSubmit('submit', 'Odeslat');
		$form->onSuccess[] = $this->formSubmitted(...);
		return $form;
	}
	public function formSubmitted(Form $form, stdClass $data): void
	{
		if ($data->newPassword !== $data->newPasswordAgain) {
			$form->addError("Nová hesla se neshodují.");
			return;
		}
		try {
			$this->sendCommand(
				new ChangePasswordCommandRequest($this->user->getId(), $this->passwords->hash(
					$data->newPassword,
				), $data->oldPassword),
			);
		}
        catch (Exception $e) {
			if ($e->getPrevious()->getCode() === 1) {
				$form->addError("Špatné heslo");
			}
		}
	}
}
