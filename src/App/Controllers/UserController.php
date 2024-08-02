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
use Exception;

class UserController extends Controller {

    public ?string $modelName = UserCollection::class;

    public function loginValidar() {
        $username = $this->request->get("usuario");
        $password = $this->request->get("password");
        if ($username && $password) {
            try {
                $user = $this->model->getUser($username);
                if (!password_verify($password, $user["password"])) {
                    throw new Exception("La contraseña es incorrecta");
                }
                //session_start();
                $_SESSION['logueado'] = true;
                $_SESSION['login'] = $user["user"];
                $this->inicioUsuario();
            } catch (Exception $e) {
                echo $this->twig->render("/portal-user/login.view.twig", [
                    "mensajeError" => "Credenciales incorrectas. Inténtelo nuevamente.",
                    "menu" => $this->menu
                ]);
            }
        } else {
            echo $this->twig->render("/portal-user/login.view.twig", [
                "mensajeError" => "Se debe especificar un usuario y su contraseña.",
                "menu" => $this->menu
            ]);
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
        
        $search = false;

        echo $this->twig->render('/portal-admin/inicio-usuario.view.twig',compact('menu','titulo','numberOfCities','numberOfBeachResorts','numberOfReservations', 'search'));
    }



}