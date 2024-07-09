<?php

namespace Tents\Core\Database;

use PDO;
use Monolog\Logger;

class QueryBuilder {

    private $pdo;
    private $logger;
    
    public function __construct(PDO $pdo, Logger $logger) {
        $this -> pdo = $pdo;
        $this -> logger = $logger;
    }

    public function select($table, $params = []) {
        $where = "1 = 1";
        $bindings = [];
    
        foreach ($params as $column => $value) {
            $where .= " AND $column = :$column";
            $bindings[":$column"] = $value;
        }
    
        $query = "SELECT * FROM $table WHERE $where";
        $statement = $this->pdo->prepare($query);
        $statement->execute($bindings);
    
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
   
    
    public function getPdo() {
        return $this->pdo;
    }

    public function insert($table, $object) {
        $columns = [];
        $placeholders = [];
        $values = [];
    
        foreach ($object->fields as $key => $value) {
            $columns[] = $key;
            $placeholders[] = ':' . $key;
            $values[':' . $key] = $value;
        }
    
        $columnString = implode(', ', $columns);
        $placeholderString = implode(', ', $placeholders);
    
        $query = "INSERT INTO $table ($columnString) VALUES ($placeholderString)";
        $statement = $this->pdo->prepare($query);
    
        foreach ($values as $placeholder => $value) {
            $statement->bindValue($placeholder, $value);
        }
        
        $statement->execute();
    }

    public function join($table, $joinTable, $joinCondition, $selectColumns = ['*'], $params = []) {
        $where = "1 = 1";
        $bindings = [];
    
        foreach ($params as $column => $value) {
            $where .= " AND $column = $value";
            $bindings[":$column"] = $value;
        }
        
        $selectColumnsString = implode(', ', $selectColumns);
        $query = "SELECT $selectColumnsString FROM $table INNER JOIN $joinTable ON $joinCondition WHERE $where";

        $sentencia = $this->pdo->prepare($query);
        $sentencia->setFetchMode(PDO::FETCH_ASSOC);
        $sentencia->execute();

        return $sentencia->fetchAll();
    }
    
    
    public function update() {

    }

    public function delete() {
        
    }
}