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

}