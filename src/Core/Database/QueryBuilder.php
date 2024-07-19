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
                    case 'not':
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

        
        //$statement->execute($bindings);


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

    // Definición de la función join
    public function join($table, $joins, $selectColumns = ['*'], $params = []) {
        $where = "1 = 1";
        //  $bindings = [];

        // Construcción de la cláusula WHERE y vinculación de parámetros
        foreach ($params as $column => $value) {
            $where .= " AND $column = $value";
         //   $bindings[":$column"] = $value;
        }

        // Construcción de la consulta SELECT con los JOINs
        $selectColumnsString = implode(', ', $selectColumns);
        $query = "SELECT $selectColumnsString FROM $table";

        foreach ($joins as $join) {
            $joinTable = $join['table'];
            $joinCondition = $join['condition'];
            $joinType = isset($join['type']) ? strtoupper($join['type']) : 'LEFT';
            $query .= " $joinType JOIN $joinTable ON $joinCondition";
        }

        // Agregar la cláusula WHERE al final de la consulta
        $query .= " WHERE $where";

        // Preparar la consulta
        $statement = $this->pdo->prepare($query);
        $statement->setFetchMode(PDO::FETCH_ASSOC);

        // var_dump($bindings);

        // // Vincular los parámetros
        // foreach ($bindings as $param => $value) {
        //     $statement->bindValue($param, $value);
        // }

       // var_dump($statement);
        //  die;

        // Ejecutar la consulta
        $statement->execute();

        // Retornar los resultados
        return $statement->fetchAll();
    }

    

    public function querySql($query, $params = []) {
        $statement = $this->pdo->prepare($query);
        $statement->setFetchMode(PDO::FETCH_ASSOC);
        foreach ($params as $param => $value) {
            $statement->bindValue($param, $value);
        };
        $statement->execute();
        return $statement->fetchAll();
    }
    
    
    public function update($table, $values = [])
    {
        $arg = "";
        $where = " 1 = 1 ";
        if (isset($values['id'])){
            $where = " id = :id ";
        }
        foreach($values as $clave => $value){
            $arg .= "{$clave}=:{$clave},";
        }
        $arg = trim($arg, ',');
        $query = "update {$table} set {$arg} where {$where}";
        if (isset($params['id'])){
            $sentencia->bindValue(":id",$params['id']);
        }
        $sentencia = $this->pdo->prepare($query);
        // var_dump($values);
        // die();
        $sentencia->setFetchMode(PDO::FETCH_ASSOC);
        $sentencia->execute($values);
    }

    public function delete() {
        
    }
}