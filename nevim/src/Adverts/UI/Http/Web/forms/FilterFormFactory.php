<?php

declare(strict_types=1);

namespace Ondra\App\Adverts\UI\Http\Web\forms;

use Nette\Application\UI\Form;
use Ondra\App\Adverts\Application\Query\Messages\Request\GetStatesQuery;
use Ondra\App\Shared\UI\Http\Web\forms\FormFactory;

class FilterFormFactory extends FormFactory
{
	public function create(): Form
	{
        $states = $this->sendQuery(new GetStatesQuery())->states;
		$form = new Form();
		$form->addInteger('max', 'Nejvyšší cena');
		$form->addInteger('min', 'Nejnižší cena');
		$form->addCheckboxList('stateId', 'Stav', $states);
		$form->addText('brand', 'Značka')->setNullable();
		$form->addSubmit('filter', 'filtrovat');
		return $form;
	}
}
