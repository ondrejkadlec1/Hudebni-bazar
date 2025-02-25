<?php

declare(strict_types=1);

namespace Ondra\App\Users\UI\Http\Web;

use Nette\Application\UI\Form;
use Nette\Security\User;
use Ondra\App\Adverts\UI\Http\Web\base\Browsable;
use Ondra\App\Adverts\UI\Http\Web\traits\Paginated;
use Ondra\App\Shared\UI\Http\Web\traits\UsersOnly;
use Ondra\App\Users\Application\Query\DTOs\SellerProfileDTO;
use Ondra\App\Users\Application\Query\Messages\GetMyProfileQuery;
use Ondra\App\Users\UI\Http\Web\forms\ChangePwdFormFactory;

final class ProfilePresenter extends Browsable
{
	use UsersOnly;
	use Paginated;
	public function __construct(
		private readonly User $user,
		private readonly ChangePwdFormFactory $factory,
	) {
	}

	public function createComponentChangePwdForm(): Form
	{
		$form = $this->factory->create();
		$form->onSuccess[] = function () {
			$this->flashMessage('Heslo bylo změněno');
			$this->redirect('Profile:default');
		};
		return $form;
	}

	public function renderDefault(): void
	{
        $this->criteria->addArray(['sellerId' => $this->user->getId()]);
		$result = $this->sendQuery(new GetMyProfileQuery())->dto;
		if ($result instanceof SellerProfileDTO) {
			$this->template->description = $result->description;
			$this->template->isSeller = true;
			$this->template->profile = $result->profile;
            return;
		}
        $this->template->profile = $result;
        $this->template->isSeller = false;
	}
}
