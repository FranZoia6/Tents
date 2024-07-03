<?php

namespace Tents\App\controllers;

use Tents\Core\Controller;
use Tents\App\Models\User;
use Tents\App\Models\UsersCollection;
use Tents\Core\Database\QueryBuilder;

class UserController extends Controller {

    public ?string $modelName = UsersCollection::class;

    public function loginValidar() {

       # $user = new User;
       # $user -> setUser($_POST['usuario']);
       # $user -> setPassword($_POST['password']);

        $userExist = $this->model->checkExists($_POST['usuario'], $_POST['password']);

        if ($userExist) {
            session_start();
            $_SESSION['logueado'] = true;
            if (isset($_POST['usuario'])) {
                $_SESSION['login'] = $_POST['usuario'];
            }
            $this -> inicioUsuario();
        } else {
            echo $this->twig->render('login.view.twig');
        }
    }  

    public function inicioUsuario() {
        echo $this->twig->render('/portal-admin/inicio-usuario.view.twig');
    }

}