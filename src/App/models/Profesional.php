<?php

namespace Paw\App\models;

use Paw\Core\Model;
use Paw\Core\Exceptions\InvalidValueFormatException;
use Exception;

class Profesional extends Model {

    public $table = 'profesional';

    public $fields = [
        "matricula" => null,
        "nombre" => null,
        "apellido" => null,
        "horario_inicio" => null,
        "horario_fin" => null,
        "duracion_turno" => null,
    ];

    public function setMatricula($matricula) {
        $this -> fields["matricula"] = $matricula;
    }

    public function setNombre(string $nombre) {
        if (strlen($nombre) > 60) {
            throw new InvalidValueFormatException("El nombre del profesional no debe ser mayor a 60 caracteres");
        }
        $this -> fields["nombre"] = $nombre;
    }

    public function setApellido(string $apellido) {
        if (strlen($apellido) > 60) {
            throw new InvalidValueFormatException("El apellido del profesional no debe ser mayor a 60 caracteres");
        }
        $this -> fields["apellido"] = $apellido;
    }

    public function setHorario_Inicio($horarioInicio) {
        $this -> fields["horario_inicio"] = $horarioInicio;
    }

    public function setHorario_Fin($horarioFin) {
        $this -> fields["horario_fin"] = $horarioFin;
    }

    public function setDuracion_Turno($duracion) {
        $this -> fields["duracion_turno"] = $duracion;
    }

    public function set(array $values) {
        foreach(array_keys($this -> fields) as $field) {
            if (!isset($values[$field])) {
                continue;
            }
            $method = "set" . ucfirst($field);
            $this -> $method($values[$field]);
        }
    }
}