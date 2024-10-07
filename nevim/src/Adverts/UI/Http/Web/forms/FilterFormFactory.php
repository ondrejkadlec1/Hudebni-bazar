<?php

declare(strict_types=1);

namespace Ondra\App\Adverts\UI\Http\Web\forms;

use Nette\Application\UI\Form;

class FilterFormFactory
{
	public function create(): Form
	{
		$states = [
			1 => 'Nový',
			2 => 'Zánovní',
			3 => 'Používaný',
			4 => 'Opotřebený',
			5 => 'Poškozený',
			6 => 'Nefunkční',
		];
		$form = new Form();
		$form->addInteger('max', 'Nejvyšší cena');
		$form->addInteger('min', 'Nejnižší cena');
		$form->addMultiSelect('stateId', 'Stav', $states);
		$form->addText('brand', 'Značka')->setNullable();
		$form->addSubmit('filter', 'filtrovat');
		return $form;
	}
}
