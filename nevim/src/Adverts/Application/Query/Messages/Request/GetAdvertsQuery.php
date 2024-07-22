<?php

namespace Ondra\App\Adverts\Application\Query\Messages;

use Ondra\App\Adverts\Application\Query\DTOs\SearchCriteria;
use Ondra\App\Shared\Application\Query\Query;

class GetAdvertsQuery implements Query
{
 private SearchCriteria $criteria;

    /**
     * @param SearchCriteria $criteria
     */
    public function __construct(SearchCriteria $criteria)
    {
        $this->criteria = $criteria;
    }

    /**
     * @return SearchCriteria
     */
    public function getCriteria(): SearchCriteria
    {
        return $this->criteria;
    }

}