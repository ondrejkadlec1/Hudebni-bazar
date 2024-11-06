<?php

declare(strict_types=1);

namespace Ondra\App\Shared\UI\Http\Web;

use Nette\Application\UI\Form;
use Nette\Application\UI\Presenter;
use Ondra\App\Adverts\Application\Query\Messages\Request\GetCategoriesQuery;
use Ondra\App\Shared\Application\CQRS;
use Ondra\App\Shared\Application\CQRSCapable;

abstract class FrontendPresenter extends Presenter implements CQRSCapable
{
	use CQRS;
	public function beforeRender(): void
	{
		parent::beforeRender();
		$queryResult = $this->sendQuery(new GetCategoriesQuery());
		$this->template->categories = $queryResult->categories;
		$this->template->subcategories = $queryResult->subcategories;
		$this->template->subsubcategories = $queryResult->subsubcategories;
	}
	public function createComponentSearchForm(): Form
	{
		$form = new Form();
		$form->addText('expression')->addRule($form::MinLength, 'Alespoň 3 znaky.', 3);
		$form->addSubmit('search', "Hledat");
		$form->onSuccess[] = [$this, 'search'];
		return $form;
	}

	public function search(\stdClass $data): void
	{
		$this->redirect(":Adverts:Browse:default", ($data->expression));
	}
}
