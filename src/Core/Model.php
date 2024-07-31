<?php

namespace Tents\Core;

use Tents\Core\Database\QueryBuilder;
use Tents\Core\Traits\Loggable;
use Exception;

class Model {
    
    use Loggable;

    public $queryBuilder;

    public function setQueryBuilder(QueryBuilder $qb) {
        $this -> queryBuilder = $qb;
    }

    public function load($id)
    {
        $params = ["id" => $id];
        $record = current($this->queryBuilder->select($this->table, $params));
        if ($record === false) {
            throw new Exception('Record not found for ID: ' . $id);
        }
        $this->set($record);
    }
}