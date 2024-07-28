<?php

namespace Tents\Core\Exceptions;

use Exception;

/**
 * Excepción lanzada cuando un valor no cumple con el formato esperado.
 */
class InvalidValueFormatException extends Exception {
    public function __construct($message = "Formato de valor no válido", $code = 0, Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }

    /**
     * Obtiene un mensaje de error personalizado.
     *
     * @return string
     */
    public function getCustomMessage() {
        return "Error en el formato del valor: " . $this->getMessage();
    }
}
