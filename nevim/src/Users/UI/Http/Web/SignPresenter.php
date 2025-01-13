<?php

declare(strict_types=1);

namespace Ondra\App\Users\UI\Http\Web;

use Nette\Application\UI\Form;
use Ondra\App\Shared\UI\Http\Web\FrontendPresenter;
use Ondra\App\Shared\UI\Http\Web\GoToPrevious;
use Ondra\App\Users\UI\Http\Web\forms\SignInFormFactory;
use Ondra\App\Users\UI\Http\Web\forms\SignUpFormFactory;

final class SignPresenter extends FrontendPresenter
{
	use GoToPrevious;

	public function __construct(private readonly SignInFormFactory $inFactory, private readonly SignUpFormFactory $upFactory)
	{
	}

	public function redirectUser(): void
	{
		if ($this->user->isloggedIn()) {
			$this->flashMessage('Pro tuto akci se musíte odhlásit.');
			$this->goToPrevious();
		}
	}
	public function actionOut(): void
	{
		$this->user->logout();
		$this->goToPrevious();
	}
	public function renderIn(): void
	{
		$this->redirectUser();
	}
	public function renderUp(): void
	{
		$this->redirectUser();
	}
	public function createComponentSignInForm(): Form
	{
		$form = $this->inFactory->create();
		$form->onSuccess[] = function (): void {
			$this->goToPrevious();
		};
		return $form;
	}

	public function createComponentSignUpForm(): Form
	{
		$form = $this->upFactory->create();
		$form->onSuccess[] = function (): void {
			$this->goToPrevious();
		};
		return $form;
	}
}
