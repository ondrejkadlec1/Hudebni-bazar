<?php

declare(strict_types=1);

namespace Ondra\App\Adverts\UI\Http\Web\forms;

use Nette\Application\UI\Form;
use Ondra\App\Adverts\Application\Query\Messages\Request\GetSubordinateCategoriesQuery;
use Ondra\App\Shared\UI\Http\Web\forms\FormFactory;

class AdvertFormFactory extends FormFactory
{
	public function create(): Form
	{
		$allCategories = $this->sendQuery(new GetSubordinateCategoriesQuery())->subordinate;

		$states = [
			1 => 'Nové',
			2 => 'Zánovní',
			3 => 'Používané',
			4 => 'Opotřebené',
			5 => 'Poškozené',
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
			->addRule($form::Range, 'Kolik že toho chceš prodat?', [1, 1000000])
			->setRequired("!!");
		$category = $form->addSelect('categoryId', 'Kategorie', $allCategories)
			->setPrompt('Zvolte kategorii')
			->setRequired("Kam to patří?");
		$subcategory = $form->addSelect('subcategoryId', 'Podkategorie')
			->setPrompt('Zvolte kategorii');
		$subsubcategory = $form->addSelect('subsubcategoryId', 'Další zařazení')
			->setPrompt('Zvolte kategorii');
		$form->addText('brand', 'Značka');
		$form->addMultiUpload('images', 'Nahrát obrázky')
			->addRule($form::MaxLength, 'Maximální počet souborů je' . $_ENV['MAX_IMAGES_PER_ADVERT'] . '.', $_ENV['MAX_IMAGES_PER_ADVERT'])
			->addRule($form::Image, 'Soubory musí být .jpg, nemo .png');
		$form->addSubmit('send', 'Zveřejnit');
        $form->addText('keepImages', 'keepImages');

        $form->onValidate[] = fn () =>
            $subcategory->setItems($this->sendQuery(new GetSubordinateCategoriesQuery($category->getValue()))->subordinate);
        $form->onValidate[] = fn () =>
            $subsubcategory->setItems($this->sendQuery(new GetSubordinateCategoriesQuery($subcategory->getValue()))->subordinate);
		return $form;
	}
}
