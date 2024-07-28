<?php

namespace Tents\App\controllers;

use Tents\Core\Controller;
use Tents\App\Models\User;
use Tents\App\Models\UserCollection;
use Tents\Core\Database\QueryBuilder;

class UserController extends Controller {

    public ?string $modelName = UserCollection::class;

    public function loginValidar() {
        try {
            $user = new User();
            $user->setUser($_POST['usuario']);
            $user->setPassword($_POST['password']);
    
            $userExist = $this->model->checkExists($user->getUser(), $user->getPassword());
            $menu = $this->menu;
    
            if ($userExist) {
                session_start();
                $_SESSION['logueado'] = true;
                if (isset($_POST['usuario'])) {
                    $_SESSION['login'] = $_POST['usuario'];
                }
                $this->inicioUsuario();
            } else {
                $mensajeError = 'Credenciales incorrectas. Inténtelo nuevamente.';
                echo $this->twig->render('/portal-user/login.view.twig', ['mensajeError' => $mensajeError, 'menu' => $menu]);
            }
        } catch (InvalidValueFormatException $e) {
            // Captura de excepciones específicas
            $mensajeError = $e->getCustomMessage();
            echo $this->twig->render('/portal-user/login.view.twig', ['mensajeError' => $mensajeError, 'menu' => $menu]);
        } catch (Exception $e) {
            // Captura de cualquier otra excepción
            $mensajeError = 'Ocurrió un error inesperado. Por favor, inténtelo más tarde.';
            echo $this->twig->render('/portal-user/login.view.twig', ['mensajeError' => $mensajeError, 'menu' => $menu]);
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