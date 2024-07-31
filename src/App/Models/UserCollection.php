<?php

namespace Tents\App\Models;

use Tents\Core\Model;
use Tents\App\Models\User;
use DateTime;
use Exception;

class UserCollection extends Model {

    public $table = 'user';

    public function getUsers() {
        $users = $this -> queryBuilder -> select('user');
        $usersCollection = [];
        
        foreach ($users as $user) {
            $newUser = new User;
            $newUser -> set($user);
            $usersCollection[] = $newUser;
        }
    
        return $usersCollection;
    }

    /**
     * Retorna los datos de un usuario solicitado.
     * @param string $user Nombre del usuario solicitado.
     * @return array
     * @throws Exception Si no existe un usuario con el nombre indicado.
     */
    public function getUser($user) {
        $users = $this->queryBuilder->selectV2("user", ["user" => $user]);
        if (empty($users)) {
            throw new Exception("El usuario '$user' no existe");
        }
        // Solo debería existir un usuario con el nombre indicado.
        return $users[0];
    }

}