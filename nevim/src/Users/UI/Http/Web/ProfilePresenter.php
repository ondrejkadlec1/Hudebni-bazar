<?php

declare(strict_types=1);

namespace Ondra\App\Users\UI\Http\Web;

use Nette\Application\UI\Form;
use Nette\Security\User;
use Ondra\App\Adverts\UI\Http\Web\AdvertsListFactory;
use Ondra\App\Adverts\UI\Http\Web\Paginated;
use Ondra\App\Shared\UI\Http\Web\FrontendPresenter;
use Ondra\App\Shared\UI\Http\Web\UsersOnly;
use Ondra\App\Users\Application\Query\DTOs\SellerProfileDTO;
use Ondra\App\Users\Application\Query\Messages\GetMyProfileQuery;
use Ondra\App\Users\UI\Http\Web\forms\ChangePwdFormFactory;

final class ProfilePresenter extends FrontendPresenter
{
	use UsersOnly;
	use Paginated;
	public function __construct(
		private readonly User $user,
		private readonly ChangePwdFormFactory $factory,
		private readonly AdvertsListFactory $advertsListFactory,
	) {
	}

	public function renderDefault(): void
	{
		$result = $this->sendQuery(new GetMyProfileQuery())->dto;
		if ($result instanceof SellerProfileDTO) {
			$this->template->description = $result->description;
			$this->template->isSeller = true;
			$this->template->profile = $result->profile;
		} else {
			$this->template->profile = $result;
			$this->template->isSeller = false;
		}
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
}
