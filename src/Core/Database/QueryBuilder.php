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
            if (is_array($value)) {
                // Handle comparison operators
                $operator = $value[0];
                $conditionValue = $value[1];
    
                switch ($operator) {
                    case '=':
                    case '<':
                    case '>':
                    case '>=':
                    case '<=':
                        // Ensure strings are quoted
                        if (is_string($conditionValue)) {
                            $conditionValue = "'" . $conditionValue . "'";
                        }
                        $where .= " AND $column $operator $conditionValue";
                        break;
                    default:
                        throw new Exception("Unsupported operator: $operator");
                }
            } else {
                // Default to equality if no operator specified
                // Ensure strings are quoted
                if (is_string($value)) {
                    $value = "'" . $value . "'";
                }
                $where .= " AND $column = $value";
            }
        }
    
        $query = "SELECT * FROM $table WHERE $where";
      //  $statement = $this->pdo->prepare($query);
      //  var_dump($query);
      //  var_dump($bindings);
        
        //$statement->execute($bindings);
      //  var_dump($query);
     //   die;

        $sentencia = $this->pdo->prepare($query);
        $sentencia->setFetchMode(PDO::FETCH_ASSOC);
        $sentencia->execute();
    
       // return $statement->fetchAll(PDO::FETCH_ASSOC);
       return $sentencia->fetchAll();
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
        $query = "SELECT $selectColumnsString FROM $table LEFT JOIN $joinTable ON $joinCondition WHERE $where";

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