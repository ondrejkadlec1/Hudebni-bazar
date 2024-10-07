<?php

declare(strict_types=1);

namespace Ondra\App\Adverts\UI\Http\Web\forms;

use Nette\Application\UI\Form;
use Ondra\App\Adverts\Application\Query\Messages\Request\GetCategoriesQuery;
use Ondra\App\Shared\UI\Http\Web\forms\FormFactory;

class AdvertFormFactory extends FormFactory
{
	public function create(): Form
	{
		$response = $this->sendQuery(new GetCategoriesQuery());
		$categories = $response->categories;
		$subcategories = $response->subcategories;
		$subsubcategories = $response->subsubcategories;

		$states = [
			1 => 'Nový',
			2 => 'Zánovní',
			3 => 'Používaný',
			4 => 'Opotřebený',
			5 => 'Poškozený',
			6 => 'Nefunkční',
		];

		$form = new Form();
		$form->addText('name', 'Název')
			->setRequired("Zadé název, ty jitrnico!");
		$form->addSelect('stateId', 'Stav', $states)
			->setPrompt('Zvolte stav předmětu')
			->setRequired("Tak je to rozflákaný nebo to valí?");
		$form->addInteger('price', 'Cena')
			->setRequired("Kolik to má stát, debile?");
		$form->addText('details', 'Podrobnosti');
		$form->addInteger('quantity', 'počet')
			->setDefaultValue(1)
			->addRule($form::Range, 'Kolik že toho chceš prodat?', [1, 10000000000])
			->setRequired("!!");
		$form->addSelect('categoryId', 'Kategorie', $categories)
			->setPrompt('Zvolte kategorii')
			->setRequired("Kam to patří?");
		$form->addSelect('subcategoryId', 'Podkategorie', $subcategories)
			->setPrompt('Zvolte kategorii')
			->setRequired("Kam to patří?");
		$form->addSelect('subsubcategoryId', 'Další zařazení', $subsubcategories)
			->setPrompt('Zvolte kategorii')
			->setRequired("Kam to patří?");
		$form->addText('brand', 'Značka');
		$form->addMultiUpload('images', 'Nahrát obrázky')
			->addRule($form::MaxLength, 'Maximální počet souborů je 10.', 10)
			->addRule($form::Image, 'Soubory musí být .jpg, nemo .png');
		$form->addSubmit('send', 'Zveřejnit');
		return $form;
	}
}
