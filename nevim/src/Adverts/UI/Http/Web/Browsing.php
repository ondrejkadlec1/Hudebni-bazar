<?php

declare(strict_types=1);

namespace Ondra\App\Adverts\UI\Http\Web;

use Nette\Application\UI\Form;
use Ondra\App\Adverts\Application\Query\DTOs\SearchCriteria;
use Ondra\App\Adverts\UI\Http\Web\forms\FilterFormFactory;

trait Browsing
{
	private string $orderBy = 'date';
	private string $direction = 'desc';
	private ?int $limit = null;
	private int $offset = 0;
	private SearchCriteria $criteria;

	public function __construct(private readonly FilterFormFactory $filterFormFactory, private readonly AdvertsListFactory $advertsListFactory)
	{
	}
	public function injectCreateCriteria(): void
	{
		$this->onStartup[] = function () {
			if ($this->getParameter('orderBy') !== null) {
				$this->orderBy = $this->getParameter('orderBy');
			}
			if ($this->getParameter('direction') !== null) {
				$this->direction = $this->getParameter('direction');
			}

			$this->criteria = new SearchCriteria(
				limit: $this->limit,
				offset: $this->offset,
				orderBy: $this->orderBy,
				direction: $this->direction,
				categoryId: (int) $this->getParameter('categoryId'),
				sellerId: $this->getParameter('sellerId'),
				maxPrice: (int) $this->getParameter('max'),
				minPrice: (int) $this->getParameter('min'),
				stateIds: $this->getParameter('stateId'),
				brands: $this->getParameter('brand'),
				expression: $this->getParameter('search'),
			);
		};
	}

	public function createComponentAdvertsList(): AdvertsListControl
	{
		return $this->advertsListFactory->create($this->criteria);
	}
	public function createComponentFilterForm(): Form
	{
        $defaults = [];
        if ($this->getParameter('max') !== null){
            $defaults['max'] = $this->getParameter('max');
        }
        if ($this->getParameter('min') !== null){
            $defaults['min'] = $this->getParameter('min');
        }
        if ($this->getParameter('brand') !== null){
            $defaults['brand'] = $this->getParameter('brand');
        }
        if ($this->getParameter('stateId') !== null){
            $defaults['stateId'] = $this->getParameter('stateId');
        }
		$form = $this->filterFormFactory->create();
        $form->setDefaults($defaults);
		$form->onSuccess[] = function (array $data): void {
			$this->redirect("this", $data);
		};
        return $form;
	}
}
