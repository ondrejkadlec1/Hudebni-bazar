<?php

namespace Ondra\App\Adverts\Application\Query;

use Ondra\App\Shared\Application\Query\Query;

class GetAdvertsQuery implements Query
{
 private \stdClass $criteria;

    /**
     * @param \stdClass $criteria
     */
    public function __construct(\stdClass $criteria)
    {
        $this->criteria = $criteria;
    }

    /**
     * @return \stdClass
     */
    public function getCriteria(): \stdClass
    {
        return $this->criteria;
    }
}