<?php

namespace Ondra\App\Adverts\UI\Http\Web\traits;

use Nette\Application\UI\Form;
use Ondra\App\Adverts\Application\Query\DTOs\SearchCriteria;
use Ondra\App\Adverts\UI\Http\Web\forms\FilterFormFactory;
use Ondra\App\Adverts\UI\Http\Web\templates\Browsing;

trait Filtered
{
    private FilterFormFactory $filterFormFactory;

    public function createComponentFilterForm(): Form
    {
        $defaults = [];
        $params = ['maxPrice', 'minPrice', 'brand', 'stateIds'];
        foreach ($params as $param) {
            if (($value = $this->getParameter($param)) !== null) {
                $defaults[$param] = $value;
            }
        }

        $form = $this->filterFormFactory->create();
        $form->setDefaults($defaults);
        $form->onSuccess[] = function (array $data): void {
            $this->redirect("this", $data);
        };
        return $form;
    }

    public function injectAddFilters(): void
    {
        $this->onStartup[] = function () {
            $params = [];
            foreach (SearchCriteria::$filters as $filter => $type){
                $value = $this->getParameter($filter);
                if ($value !== null) {
                    $params[$filter] = match ($type) {
                        'integer' => (int)$value,
                        default => $value,
                    };
                }
            }
            $this->criteria->addArray($params);
        };
    }

    public function injectFilterFactory(FilterFormFactory $factory): void
    {
        $this->filterFormFactory = $factory;
    }
}