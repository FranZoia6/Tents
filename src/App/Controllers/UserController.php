<?php

namespace Tents\App\controllers;

use Tents\Core\Controller;
use Tents\App\Models\User;
use Tents\App\Models\UserCollection;
use Tents\Core\Database\QueryBuilder;
use Tents\App\Models\BeachResortCollection;
use Tents\App\Models\BeachResort;
use Tents\App\Models\CityCollection;
use Tents\App\Models\City;
use Tents\App\Models\Reservation;
use Tents\App\Models\ReservationCollection;

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
        $cityCollection = new CityCollection;
        $cityCollection->setQueryBuilder($this->model->queryBuilder);
        $cities = $cityCollection->getAll();
        $numberOfCities = count($cities);
        
        $beachResortCollection = new BeachResortCollection;
        $beachResortCollection->setQueryBuilder($this->model->queryBuilder);
        $beachResorts = $beachResortCollection->getAll();
        $numberOfBeachResorts = count($beachResorts);
        
        $reservationCollection = new ReservationCollection;
        $reservationCollection->setQueryBuilder($this->model->queryBuilder);
        $reservations = $reservationCollection->getAll();
        $numberOfReservations = count($reservations);

        echo $this->twig->render('/portal-admin/inicio-usuario.view.twig',compact('menu','titulo','numberOfCities','numberOfBeachResorts','numberOfReservations'));
    }



}