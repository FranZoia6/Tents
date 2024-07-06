<?php

namespace Tents\Core;

class Request {
    
    public function uri() {
        return parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
    }

    public function method() {
        return $_SERVER['REQUEST_METHOD'];
    }

    public function route() {
        return [
            $this -> uri(),
            $this -> method()
        ];
    }

    public function get($key)
    {
        return $_POST[$key] ?? $_GET[$key] ?? null;
    }

    /**
     * Determina si la solicitud es AJAX.
     * @return bool
     * @see https://css-tricks.com/snippets/php/detect-ajax-request/
     */
    public function isAjax() {
        return !empty($_SERVER["HTTP_X_REQUESTED_WITH"])
                && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"]) == "xmlhttprequest";
    }

}