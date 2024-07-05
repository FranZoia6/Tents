<?php

namespace Tents\App\Controllers;
use Tents\Core\Controller;

class ErrorController extends Controller
{
    public function notFound()
    {
        http_response_code(404);
        $titulo = "Error 404";
        $menu = $this->menu;
        echo $this->twig->render('not-found.view.twig', compact('menu','titulo'));
    }

    public function internalError()
    {
        http_response_code(500);
        require $this->viewsDir .'internal-error.view.php';
    }
    
}