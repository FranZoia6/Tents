<?php

namespace Tents\App\Models;

use Tents\Core\Model;
use Tents\Core\Exceptions\InvalidValueFormatException;
use Exception;

class User extends Model {

    public $table = 'user';

    public $fields = [
        "user" => null,
        "password" => null,
    ];

    public function setUser(string $user) {
        if (strlen($user) < 6) {
            throw new InvalidValueFormatException("El nombre de usuario debe ser mayor a 6 caracteres.");
        }
        $this -> fields["user"] = $user;
    }

    public function setPassword($password) {

        // Verificar que la contraseña cumpla con ciertas reglas
        if (strlen($password) < 8) {
            throw new InvalidPasswordException("La contraseña debe tener al menos 8 caracteres.");
        }
    
        if (!preg_match("/[a-z]/", $password) || !preg_match("/[A-Z]/", $password)) {
            throw new InvalidPasswordException("La contraseña debe contener al menos una letra mayúscula y una letra minúscula.");
        }
    
        if (!preg_match("/\d/", $password)) {
            throw new InvalidPasswordException("La contraseña debe contener al menos un número.");
        }
    
        // Asignar la contraseña solo si pasa las validaciones
        $this->fields["password"] = $password;
    }

    public function set(array $values) {
        foreach(array_keys($this -> fields) as $field) {
            if (!isset($values[$field])) {
                continue;
            }
            $method = "set" . ucfirst($field);
            $this -> $method($values[$field]);
        }
    }

}