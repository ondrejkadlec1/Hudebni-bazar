<?php

declare(strict_types=1);

namespace Ondra\App\Adverts\UI\Http\Web\forms;

use Nette\Application\UI\Form;
use Ondra\App\Adverts\Application\Query\Messages\Request\GetStatesQuery;
use Ondra\App\Adverts\Application\Query\Messages\Request\GetSubordinateCategoriesQuery;
use Ondra\App\Shared\UI\Http\Web\forms\FormFactory;

final class AdvertFormFactory extends FormFactory
{
	public function create(): Form
	{
		$allCategories = $this->sendQuery(new GetSubordinateCategoriesQuery())->subordinate;

		$states = $this->sendQuery(new GetStatesQuery())->states;

		$form = new Form();
		$form->addText('name', 'Název')
			->setRequired("Zadejte název.");
		$form->addSelect('stateId', 'Stav', $states)
			->setPrompt('Zvolte stav předmětu')
			->setRequired("Zvolte stav.");
		$form->addInteger('price', 'Cena')
			->setRequired("Zadejte cenu.");
		$form->addText('details', 'Podrobnosti');
		$form->addInteger('quantity', 'počet')
			->setDefaultValue(1)
			->addRule($form::Range, 'Kámo tohle je bazar…', [1, 9999])
			->setRequired("!!");
		$category = $form->addSelect('categoryId', 'Kategorie', $allCategories)
			->setPrompt('Zvolte kategorii')
			->setRequired("Zvolte zařazení.");
		$subcategory = $form->addSelect('subcategoryId', 'Podkategorie')
			->setPrompt('Zvolte kategorii');
		$subsubcategory = $form->addSelect('subsubcategoryId', 'Další zařazení')
			->setPrompt('Zvolte kategorii');
		$form->addText('brand', 'Značka');
		//        TODO: use some database of brands or decide to remove brands completely
		$form->addMultiUpload(
			'images',
			'Nahrát obrázky',
		)
			->addRule(
				$form::MaxLength,
				'Maximální počet souborů je' . $_ENV['MAX_IMAGES_PER_ADVERT'] . '.',
				$_ENV['MAX_IMAGES_PER_ADVERT'],
			)
			->addRule($form::Image, 'Soubory musí být .jpg, nebo .png');
		$form->addSubmit('send', 'Zveřejnit');
		$form->addText('keepImages', 'keepImages');

		$form->onValidate[] = fn () =>
			$subcategory->setItems($this->sendQuery(new GetSubordinateCategoriesQuery($category->getValue()))->subordinate);
		$form->onValidate[] = fn () =>
			$subsubcategory->setItems(
				$this->sendQuery(new GetSubordinateCategoriesQuery($subcategory->getValue()))->subordinate,
			);
		return $form;
	}
}
