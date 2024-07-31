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

    /**
     * @deprecated Usar la función "selectV2".
     */
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

    /**
     * Ejecuta una consulta "select" en la base de datos.
     * Esta función es segura contra inyecciones SQL.
     * @param string $table      Nombre de la tabla sobre la cual se ejecutará
     * la consulta.
     * @param array  $conditions Condiciones para la consulta. Es un array
     * asociativo cuyas claves son nombres de columnas y los valores pueden ser
     * un valor específico (para una condición de igualdad) o un array de dos
     * elementos: el operador de la condición y el valor.
     * Ejemplo: ["city" => 2, "shade" => 3, "date" => ["<", "2024-08-02"]].
     * @return array
     * @throws Exception Si $conditions incluye algún operador desconocido.
     */
    public function selectV2($table, $conditions = []) {
        // Valores a bindear a la query.
        $args = [];
        // Partes de la cláusula "where".
        $where = [];
        foreach ($conditions as $column => $value) {
            if (is_array($value)) {
                $operator = $value[0];
                $args[] = $value[1];
            } else {
                $operator = "=";
                $args[] = $value;
            }
            switch ($operator) {
                case "=":
                case "<":
                case "<=":
                case ">":
                case ">=":
                case "not":
                    $where[] = "$column $operator ?";
                    break;
                default:
                    throw new Exception("El operador '$operator' no está soportado");
            }
        }
        $query = "SELECT * FROM $table";
        if (!empty($conditions)) {
            $query .= " WHERE " . implode(" AND ", $where);
        }
        $statement = $this->pdo->prepare($query);
        $statement->setFetchMode(PDO::FETCH_ASSOC);
        foreach ($args as $idx => $arg) {
            $statement->bindValue($idx + 1, $arg);
        }
        $statement->execute();
        return $statement->fetchAll();
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
    
        $columnString = "`".implode('`, `', $columns)."`";
        $placeholderString = implode(', ', $placeholders);
    
        $query = "INSERT INTO $table ($columnString) VALUES ($placeholderString)";
        $statement = $this->pdo->prepare($query);
    
        foreach ($values as $placeholder => $value) {
            $statement->bindValue($placeholder, $value);
        }
        


        $statement->execute();
        
        return $this->pdo->lastInsertId();
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

    public function delete($table, $values = []) {
        $where = "1 = 1";
        foreach ($values as $key => $value) {
            $where .= " AND {$key} = :{$key}";
        }
    
        $query = "DELETE FROM {$table} WHERE {$where}";
    
        $sentencia = $this->pdo->prepare($query);
    
        $sentencia->setFetchMode(PDO::FETCH_ASSOC);
    
        $sentencia->execute($values);
    }
    
}