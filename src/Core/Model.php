<?php

namespace Tents\Core;

use Tents\Core\Database\QueryBuilder;
use Tents\Core\Traits\Loggable;

class Model {
    
    use Loggable;

    public $queryBuilder;

    public function setQueryBuilder(QueryBuilder $qb) {
        $this -> queryBuilder = $qb;
    }
}