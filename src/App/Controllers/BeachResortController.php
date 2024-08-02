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
use Tents\App\Models\Service;
use Exception;
use SimpleXMLElement;

class BeachResortController extends Controller {
    private const MAX_FILE_SIZE = 5 * 1024 * 1024; // 5 MB
    private const ALLOWED_PROFILE_EXTENSIONS = ['jpg', 'jpeg', 'png', 'gif'];
    private const ALLOWED_SVG_EXTENSIONS = ['svg'];
    private string $uploadDirPerfil = '././imagenes/beachResorts/perfiles/';
    private string $uploadDirSvg = '././imagenes/beachResorts/svgs/';
    
    public string $viewsDir;
    public $menu;

    public ?string $modelName = BeachResortCollection::class;

    public function index() {
        $titulo = "Balnearios";
        $beachResorts = $this->model->getEnable();
        $menu = $this->menu;
        $cityCollection = new CityCollection;
        $cityCollection->setQueryBuilder($this->model->queryBuilder);
        $cities = $cityCollection->getAll();

        echo $this->twig->render('/portal-user/beachResort.index.view.twig', compact('menu', 'titulo', 'beachResorts', 'cities'));
    }

    public function get() {
        $beachResortId = $this->request->get('id');
        $beachResort = $this->model->get($beachResortId);
        $menu = $this->menu;
        $cityCollection = new CityCollection;
        $cityCollection->setQueryBuilder($this->model->queryBuilder);
        $city = $cityCollection->get($beachResort->fields["city"]);
        $serviceBeachResortCollection = new ServiceBeachResortCollection;
        $serviceBeachResortCollection->setQueryBuilder($this->model->queryBuilder);
        $servicesBeachResorts = $serviceBeachResortCollection->getByBeachResort($beachResort->fields["id"]);
        $serviceCollection = new ServiceCollection;
        $serviceCollection->setQueryBuilder($this->model->queryBuilder);
        $service_colection = [];
        $start_date = $this->request->get('start_date');
        $end_date = $this->request->get('end_date');

        foreach ($servicesBeachResorts as $servicesBeachResort){
            $service_colection[] = $serviceCollection->get($servicesBeachResort["service"])->fields;
        }
        echo $this->twig->render('/portal-user/beachResort.view.twig', compact('menu', 'beachResort', 'city', 'service_colection', 'start_date', 'end_date'));
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
            $cityCollection->setQueryBuilder($this->model->queryBuilder);
            $city = $cityCollection->get($cityId);
            echo $this->twig->render('beachResortsCity.view.twig', compact('menu', 'beachResorts', 'city'));
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header("Location: /");
            exit;
        }
    }

    public function adminBeachResor() {
        if ($this->isLogged()) {
            $titulo = "Balnearios";
            $menu = $this->menuAdmin;
            $beachResorts = $this->model->obtenerNombres();
            echo $this->twig->render('/portal-admin/adminBeachResor.view.twig', compact('menu', 'titulo', 'beachResorts'));
        } else {
            $mensajeError = 'Prueba';
            $menu = $this->menu;
            echo $this->twig->render('/portal-user/login.view.twig', ['mensajeError' => $mensajeError, 'menu' => $menu]);
        }
    }

    public function new($error = '') {
        if ($this->isLogged()) {
            $menu = $this->menuAdmin;
            $cityCollection = new CityCollection;
            $cityCollection->setQueryBuilder($this->model->queryBuilder);
            $cities = $cityCollection->getAll();
            echo $this->twig->render('/portal-admin/newBeachResort.view.twig', compact('menu', 'cities', 'error'));
        } else {
            header("Location: /");
        }
    }

    private function validateFileSize($fileKey): void {
        if ($_FILES[$fileKey]['size'] > self::MAX_FILE_SIZE) {
            throw new Exception("El archivo $fileKey excede el tamaño máximo permitido de 5 MB.");
        }
    }

    private function validateAndMoveFile($fileKey, $destination, array $allowedExtensions): void {
        $this->validateFileSize($fileKey);

        if (empty($_FILES[$fileKey]['tmp_name'])) {
            throw new Exception("Debes adjuntar un archivo $fileKey");
        }

        $fileName = $_FILES[$fileKey]['name'];
        $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        if (!in_array($extension, $allowedExtensions)) {
            throw new Exception("Formato de archivo $fileKey no permitido");
        }

        if (!move_uploaded_file($_FILES[$fileKey]['tmp_name'], $destination)) {
            throw new Exception("Error al mover el archivo $fileKey");
        }
    }

    public function submit() {
        
        if ($this -> isLogged()) {
            try {
                $profileFileName = $_POST['name'] . '_perfil.' . strtolower(pathinfo($_FILES['imagen_perfil']['name'], PATHINFO_EXTENSION));
                $svgFileName = $_POST['name'] . '.' . strtolower(pathinfo($_FILES['imagen_svg']['name'], PATHINFO_EXTENSION));
                
                $this->validateAndMoveFile('imagen_perfil', $this->uploadDirPerfil . $profileFileName, self::ALLOWED_PROFILE_EXTENSIONS);
                $this->validateAndMoveFile('imagen_svg', $this->uploadDirSvg . $svgFileName, self::ALLOWED_SVG_EXTENSIONS);
    
                $svgContent = file_get_contents($this->uploadDirSvg . $svgFileName);
                $svg = new SimpleXMLElement($svgContent);
                
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

                $beachResort = new BeachResort;
                $beachResort->setName($_POST['name']);
                $beachResort->setDescription($_POST['description']);
                $beachResort->setCity($_POST['city']);
                $beachResort->setState(1);
                $beachResort->setStreet($_POST['street']);
                $beachResort->setNumber($_POST['number']);
                $beachResort->setLat($_POST['latitud']);
                $beachResort->setLon($_POST['longitud']);
                $beachResort->setImg($this->uploadDirPerfil . $profileFileName);
                $this->model->insertBeachResort($beachResort);
    
                $newBeachResort = $this->model->getByName($beachResort->fields['name']);
                $servicesInt = array_map('intval', $_POST['services']);
                $serviceBeachResortCollection = new ServiceBeachResortCollection;
                $serviceBeachResortCollection->setQueryBuilder($this->model->queryBuilder);
    
                foreach ($servicesInt as $service) {
                    $newServiceBeachResort = new ServiceBeachResort();
                    $newServiceBeachResort->setBeachResort($newBeachResort[0]['id']);
                    $newServiceBeachResort->setService($service);
                    $serviceBeachResortCollection->insert($newServiceBeachResort);
                }
    
                $unitCollection = new UnitCollection;
                $unitCollection->setQueryBuilder($this->model->queryBuilder);
    
                $number = 1;
                for ($i = 1; $i <= $carpas_count; $i++) {
                    $carpa = new Unit;
                    $carpa->setBeachResort($newBeachResort[0]['id']);
                    $carpa->setShade(1);
                    $carpa->setNumber($number++);
                    $carpa->setPrice($_POST['precioCarpas']);
                    $unitCollection->insertUnit($carpa);
                }
    
                for ($i = 1; $i <= $sombrillas_count; $i++) {
                    $sombrilla = new Unit;
                    $sombrilla->setBeachResort($newBeachResort[0]['id']);
                    $sombrilla->setShade(2);
                    $sombrilla->setNumber($number++);
                    $sombrilla->setPrice($_POST['precioSombrillas']);
                    $unitCollection->insertUnit($sombrilla);
                }
    
                $this->adminBeachResor();
            } catch (Exception $e) {
                $error = $e->getMessage();
                $this->new($error);
            }
        } else {
            header("Location: /");
        }
        
    }

    public function enable() {
        $id = $_POST['idbeachresort'];
        $this->model->updateBeachResortState($id, 1);
        header("Location: /adminBeachResor");
        exit();
    }

    public function disable() {
        $id = $_POST['idbeachresort'];
        $this->model->updateBeachResortState($id, 2);
        header("Location: /adminBeachResor");
        exit();
    }

    public function edit() {

        if ($this -> isLogged()) {
            $beachResortId = $this->request->get('beachResort');
            $menu = $this->menuAdmin;
    
            $beachResortCollection = new BeachResortCollection;
            $beachResortCollection->setQueryBuilder($this->model->queryBuilder);
            $beachResort = $beachResortCollection->get($beachResortId);
    
            $cityCollection = new CityCollection;
            $cityCollection->setQueryBuilder($this->model->queryBuilder);
            $cities = $cityCollection->getAll();
    
            $serviceBeachResortCollection = new ServiceBeachResortCollection;
            $serviceBeachResortCollection->setQueryBuilder($this->model->queryBuilder);
            $servicesBeachResort = $serviceBeachResortCollection->getByBeachResort($beachResortId);
    
            $serviceCollection = new ServiceCollection;
            $serviceCollection->setQueryBuilder($this->model->queryBuilder);
            $services = $serviceCollection->getAll();
    
            $unitCollection = new UnitCollection;
            $unitCollection->setQueryBuilder($this->model->queryBuilder);
            $units = $unitCollection->getByBeachResort($beachResortId);
    
            $precioCarpas = "";
            $precioSombrillas = "";
            foreach ($units as $unit) {
                $sombra = $unit->fields['shade'];
                if ($sombra == 1 && !$precioCarpas) {
                    $precioCarpas = $unit->fields['price'];
                }
                if ($sombra == 2 && !$precioSombrillas) {
                    $precioSombrillas = $unit->fields['price'];
                }
            }
    
            echo $this->twig->render('/portal-admin/editBeachResort.view.twig', compact('menu', 'cities', 'beachResort', 'servicesBeachResort', 'services', 'units', 'precioCarpas', 'precioSombrillas'));
        } else {
            header("Location: /");
            exit();
        }
        
    }

    public function submitEdit() {
        if ($this -> isLogged()) {
            try {
                $beachResort = new BeachResort;
                $beachResort->setId($_POST['id']);
                $beachResort->setName($_POST['name']);
                $beachResort->setDescription($_POST['description']);
                $beachResort->setCity($_POST['city']);
                $beachResort->setStreet($_POST['street']);
                $beachResort->setNumber($_POST['number']);
                $beachResort->setLat($_POST['latitud']);
                $beachResort->setLon($_POST['longitud']);
                $this->model->updateBeachResort($beachResort);
    
                $beachResortId = $beachResort->fields['id'];
                $servicesInt = array_map('intval', $_POST['services']);
                $serviceBeachResortCollection = new ServiceBeachResortCollection;
                $serviceBeachResortCollection->setQueryBuilder($this->model->queryBuilder);
                $servicesBeachResort = $serviceBeachResortCollection->getByBeachResort($beachResortId);
    
                foreach ($servicesBeachResort as $service) {
                    $serviceBeachResortCollection->delete($service);
                }
    
                foreach ($servicesInt as $service) {
                    $newServiceBeachResort = new ServiceBeachResort();
                    $newServiceBeachResort->setBeachResort($beachResortId);
                    $newServiceBeachResort->setService($service);
                    $serviceBeachResortCollection->insert($newServiceBeachResort);
                }
    
                $unitCollection = new UnitCollection;
                $unitCollection->setQueryBuilder($this->model->queryBuilder);
                $units = $unitCollection->getByBeachResort($beachResortId);
    
                foreach ($units as $unit) {
                    if ($unit->fields['shade'] == 1) {
                        $unit->setPrice($_POST['precioCarpas']);
                    } else {
                        $unit->setPrice($_POST['precioSombrillas']);
                    }
                    $unitCollection->updatePriceUnit($unit);
                }
    
                header("Location: /adminBeachResor");
                exit();
            } catch (Exception $e) {
                $error = $e->getMessage();
                $this->new($error);
            }
        } else {
            header("Location: /");
            exit();
        }
        
    }
}
