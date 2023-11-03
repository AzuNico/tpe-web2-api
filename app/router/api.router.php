<?php
require_once 'api.route.php';
class Router {
    private $routeTable = [];
    private $defaultRoute;

    public function __construct() {
        $this->defaultRoute = null;
    }

    public function route($url, $verb) {
        //$ruta->url //no compila!
        foreach ($this->routeTable as $route) {
            if($route->match($url, $verb)){
                //TODO: ejecutar el controller//ejecutar el controller
                // pasarle los parametros
                $route->run();
                return;
            }
        }
        //Si ninguna ruta coincide con el pedido y se configuró ruta por defecto.
        if ($this->defaultRoute != null)
            $this->defaultRoute->run();
    }
    
    public function addRoute ($url, $verb, $controller, $method) {
        $this->routeTable[] = new Route($url, $verb, $controller, $method);
    }

    public function setDefaultRoute($controller, $method) {
        $this->defaultRoute = new Route("", "", $controller, $method);
    }
}
