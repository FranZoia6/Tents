<?php

namespace Tents\App\controllers;

use Tents\Core\Controller;
use Tents\App\Models\User;
use Tents\App\Models\UserCollection;
use Tents\Core\Database\QueryBuilder;

class UserController extends Controller {

    public ?string $modelName = UserCollection::class;

    public function loginValidar() {

       # $user = new User;
       # $user -> setUser($_POST['usuario']);
       # $user -> setPassword($_POST['password']);

        $userExist = $this->model->checkExists($_POST['usuario'], $_POST['password']);
        $menu = $this->menu;
        

        if ($userExist) {
            session_start();
            $_SESSION['logueado'] = true;
            if (isset($_POST['usuario'])) {
                $_SESSION['login'] = $_POST['usuario'];
            }
            $this -> inicioUsuario();
        } else {
            $mensajeError = 'Credenciales incorrectas. Intentelo nuevamente.';
            echo $this->twig->render('login.view.twig', ['mensajeError' => $mensajeError, 'menu' => $menu]);
        }
    }  

    public function inicioUsuario() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $titulo = "Admin";
        $menu = $this->menuAdmin;
        echo $this->twig->render('/portal-admin/inicio-usuario.view.twig',compact('menu','titulo'));
    }



}