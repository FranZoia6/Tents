<?php

namespace Tents\App\Controllers;

use Tents\Core\Controller;
use Tents\App\Models\BeachResortCollection;
use Tents\Core\Database\QueryBuilder;
use Tents\App\Models\BeachResort;
use Tents\App\Models\CityCollection;
use Tents\App\Models\ServiceBeachResortCollection;
use Tents\App\Models\ServiceCollection;
use Tents\App\Models\UnitCollection;
use Tents\App\Models\Unit;
use Tents\App\Models\ServiceBeachResort;

use Exception;

use SimpleXMLElement;


class BeachResortController extends Controller {

    public string $viewsDir;
    public $menu;

    public ?string $modelName = BeachResortCollection::class;
       
    public function index() {
        $titulo = "Balnearios";
        $beachResorts = $this->model->getEnable();
        $menu = $this->menu;
        $cityCollection = new CityCollection;
        $cityCollection ->setQueryBuilder($this->model->queryBuilder);
        $cities = $cityCollection->getAll();

        echo $this->twig->render('/portal-user/beachResort.index.view.twig', compact('menu','titulo','beachResorts', 'cities'));
    }

    public function get() {
        $beachResortId = $this->request->get('id');
        $beachResort = $this -> model -> get($beachResortId);
        $menu = $this->menu;
        $cityCollection = new CityCollection;
        $cityCollection ->setQueryBuilder($this->model->queryBuilder);
        $city = $cityCollection->get($beachResort->fields["city"]);
        $serviceBeachResortCollection = new ServiceBeachResortCollection;
        $serviceBeachResortCollection -> setQueryBuilder($this->model->queryBuilder);
        $servicesBeachResorts = $serviceBeachResortCollection-> getByBeachResort($beachResort->fields["id"]);
        $serviceCollection = new ServiceCollection;
        $serviceCollection -> setQueryBuilder($this->model->queryBuilder);
        $service_colection = [];
        $start_date = $this->request->get('start_date');
        $end_date = $this->request->get('end_date');

        foreach ($servicesBeachResorts as $servicesBeachResort){
            $service_colection[] = $serviceCollection->get($servicesBeachResort["service"])->fields;
        }
        echo $this->twig->render('/portal-user/beachResort.view.twig', compact('menu','beachResort',"city","service_colection",
                                                                  'start_date', 'end_date'));  
    }

    public function getByCity() {
        $cityId = $this->request->get('cityId');
        $beachResorts = $this->model->getByCity($cityId);
        
        header('Content-Type: application/json');
        echo json_encode($beachResorts, JSON_UNESCAPED_UNICODE);
    }

    public function getAllByCity() {
        $menu = $this->menu;
        $cityId = $this->request->get('id');
        try {
            $beachResorts = $this->model->getByCity($cityId);
            $cityCollection = new CityCollection;
            $cityCollection ->setQueryBuilder($this->model->queryBuilder);
            $city = $cityCollection->get($cityId);
            echo $this->twig->render('beachResortsCity.view.twig', compact('menu','beachResorts','city'));  
        } catch (Exception $e) {
            session_start();
            $_SESSION['error'] = $e->getMessage();
            header("Location: /");
            exit;

        }

    }

    public function adminBeachResor() {
        session_start();
        if (isset($_SESSION['logueado'])) {
            $titulo = "Balnearios";
            $menu = $this->menuAdmin;
            $beachResorts = $this->model->obtenerNombres();
            echo $this->twig->render('/portal-admin/adminBeachResor.view.twig',compact('menu','titulo','beachResorts'));
        }else {
            $mensajeError = 'Prueba';
            $menu = $this->menu;
            echo $this->twig->render('/portal-user/login.view.twig', ['mensajeError' => $mensajeError, 'menu' => $menu]);
        }
    }

    public function new($error='') {
        $menu = $this->menuAdmin;
        $cityCollection = new CityCollection;
        $cityCollection ->setQueryBuilder($this->model->queryBuilder);
        $cities = $cityCollection->getAll();
        if ($error) {
            echo $this->twig->render('/portal-admin/newBeachResort.view.twig', compact('menu','cities', 'error'));
        } else {
            echo $this->twig->render('/portal-admin/newBeachResort.view.twig', compact('menu','cities'));
        }
    }

    public function submit() {
        // Directorios donde se guardarán las imágenes subidas
        $uploadDirPerfil = '././imagenes/beachResorts/perfiles/';
        $uploadDirSvg = '././imagenes/beachResorts/svgs/';
    
        try {
            // Verificar si se subió la imagen de perfil
            if (empty($_FILES['imagen_perfil']['tmp_name'])) {
                throw new Exception("Debes adjuntar una imagen de perfil");
            }
    
            // Verificar si se subió el archivo SVG
            if (empty($_FILES['imagen_svg']['tmp_name'])) {
                throw new Exception("Debes adjuntar el archivo SVG de distribución");
            }
    
            // Validar la extensión de la imagen de perfil
            $imagenPerfil = $_FILES['imagen_perfil']['name'];
            $extensionPerfil = strtolower(pathinfo($imagenPerfil, PATHINFO_EXTENSION));
            $formatos_permitidos_perfil = ['jpg', 'jpeg', 'png', 'gif'];
    
            if (!in_array($extensionPerfil, $formatos_permitidos_perfil)) {
                throw new Exception("Formato de imagen de perfil no permitido");
            }
    
            // Validar la extensión del archivo SVG
            $imagenSvg = $_FILES['imagen_svg']['name'];
            $extensionSvg = strtolower(pathinfo($imagenSvg, PATHINFO_EXTENSION));
            $formatos_permitidos_svg = ['svg'];
    
            if (!in_array($extensionSvg, $formatos_permitidos_svg)) {
                throw new Exception("Formato del archivo SVG no permitido");
            }
    
            // Ruta y nombre del archivo de imagen de perfil
            $uploadFilePerfil = $uploadDirPerfil . $_POST['name'] . '_perfil.' . $extensionPerfil;
    
            // Ruta y nombre del archivo SVG
            $uploadFileSvg = $uploadDirSvg . $_POST['name'] . '.' . $extensionSvg;

            // Mover los archivos subidos al directorio de destino
            if (!move_uploaded_file($_FILES['imagen_perfil']['tmp_name'], $uploadFilePerfil)) {
                throw new Exception("Error al mover la imagen de perfil");
            }
    
            if (!move_uploaded_file($_FILES['imagen_svg']['tmp_name'], $uploadFileSvg)) {
                throw new Exception("Error al mover el archivo SVG");
            }
    
            // Procesar el SVG para contar elementos
            $svg_content = file_get_contents($uploadFileSvg); // Obtener contenido del archivo SVG
    
            // Crear objeto SimpleXMLElement desde el contenido del SVG
            $svg = new SimpleXMLElement($svg_content);
    
            // Contador para <ellipse>
            $sombrillas_count = 0;
    
            // Contar todos los elementos <ellipse> dentro del SVG
            foreach ($svg->xpath('//svg:ellipse') as $sombrilla) {
                $sombrillas_count++;
            }
    
            // Contador para <rect> con id de un solo caracter
            $carpas_count = 0;
    
            // Contar todos los <rect> dentro del SVG que tienen un id compuesto únicamente por dígitos
            foreach ($svg->xpath('//svg:rect') as $rect) {
                $id = (string) $rect['id'];
                if (preg_match('/^\d+$/', $id)) {
                    $carpas_count++;
                }
            }
    
            // Aquí puedes guardar los resultados en la base de datos u hacer cualquier otra operación necesaria
            
            $beachResort = new BeachResort;
            $beachResort->setName($_POST['name']);
            $beachResort->setDescription($_POST['description']);
            $beachResort->setCity($_POST['city']);
            $beachResort->setState(1);
            $beachResort->setLat($_POST['latitud']);
            $beachResort->setLon($_POST['longitud']);
            $beachResort->setImg($uploadFilePerfil); // Guardar la imagen de perfil en el modelo
            $this->model->insertBeachResort($beachResort);
    
            $newBeachResort = $this->model->getByName($beachResort->fields['name']);
            
            // Insertar servicios

            $services = $_POST['services']; // Esto será un array con los servicios seleccionados.

            // Convertir los valores a enteros
            $servicesInt = array_map('intval', $services);

            $serviceBeachResortCollection = new ServiceBeachResortCollection;
            $serviceBeachResortCollection->setQueryBuilder($this->model->queryBuilder);

            foreach ($servicesInt as $service) {
                // var_dump($service);
                // die;
                $newServiceBeachResort = new ServiceBeachResort();
                $newServiceBeachResort -> setBeachResort($newBeachResort[0]['id']);
                $newServiceBeachResort -> setService($service);
                $id = $serviceBeachResortCollection -> insert($newServiceBeachResort);
            }

            // Insertar unidades de carpas
            $unitCollection = new UnitCollection;
            $unitCollection->setQueryBuilder($this->model->queryBuilder);
    
            $number = 1;
    
            // Iterar $carpas_count veces
            for ($i = 1; $i <= $carpas_count; $i++) {
                $carpa = new Unit;
                $carpa->setBeachResort($newBeachResort[0]['id']);
                $carpa->setShade(1);
                $carpa->setNumber($number);
                $carpa->setPrice($_POST['precioCarpas']);
                $unitCollection->insertUnit($carpa);
                $number++;
            }
    
            // Iterar $sombrillas_count veces
            for ($i = 1; $i <= $sombrillas_count; $i++) {
                $sombrilla = new Unit;
                $sombrilla->setBeachResort($newBeachResort[0]['id']);
                $sombrilla->setShade(2);
                $sombrilla->setNumber($number);
                $sombrilla->setPrice($_POST['precioSombrillas']);
                $unitCollection->insertUnit($sombrilla);
                $number++;
            }
    
            $this->adminBeachResor();
    
        } catch (Exception $e) {
            session_start();
            $error = $e -> getMessage();
            $this -> new($error);
        }
    }
    
    public function enable() {
        $id = $_POST['idbeachresort'];
        $state = 1;
        $this->model->updateBeachResortState($id,$state);
        header("Location: /adminBeachResor");
        exit();
    }

    public function disable() {
        $id = $_POST['idbeachresort'];
        $state = 2;
        $this->model->updateBeachResortState($id,$state);
        header("Location: /adminBeachResor");
        exit();
    }

    public function edit() {

        $beachResortId = $this->request->get('beachResort');
        $menu = $this->menuAdmin;

        $cityCollection = new CityCollection;
        $cityCollection ->setQueryBuilder($this->model->queryBuilder);
        $cities = $cityCollection->getAll();

        $beachResort = $this -> model -> obtenerCiudad($beachResortId);

        echo $this->twig->render('/portal-admin/editBeachResort.view.twig', compact('cities','beachResort'));
    }

    public function set() {

    }

}