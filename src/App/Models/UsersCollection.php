<?php

namespace Tents\App\Models;

use Tents\Core\Model;
use Tents\App\Models\User;
use DateTime;

class UsersCollection extends Model {

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

    public function checkExists($usuario, $password) {

        $users = $this -> queryBuilder -> select('user', ['user' => $usuario, 'password' => $password]);

        if (count($users) !== 0)
            return true;
        else
            return false;

    }

}