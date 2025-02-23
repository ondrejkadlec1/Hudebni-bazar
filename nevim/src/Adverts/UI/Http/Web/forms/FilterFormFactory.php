<?php

declare(strict_types=1);

namespace Ondra\App\Adverts\UI\Http\Web\forms;

use Nette\Application\UI\Form;
use Ondra\App\Adverts\Application\Query\Messages\Request\GetStatesQuery;
use Ondra\App\Shared\UI\Http\Web\forms\FormFactory;

final class FilterFormFactory extends FormFactory
{
	public function create(): Form
	{
		$states = $this->sendQuery(new GetStatesQuery())->states;
		$form = new Form();
		$form->addInteger('maxPrice', 'Nejvyšší cena');
		$form->addInteger('minPrice', 'Nejnižší cena');
		$form->addCheckboxList('stateIds', 'Stav', $states);
		$form->addText('brand', 'Značka')->setNullable();
		$form->addSubmit('filter', 'filtrovat');
		return $form;
	}
}
