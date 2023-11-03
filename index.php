<?php
require_once 'config.php';
require_once 'app/router/api.router.php';

$router = new Router();

#                 endpoint      verbo     controller           método
$router->addRoute('owners',     'GET',    'OwnerController',   'get');
$router->addRoute('owners',     'POST',   'OwnerController',   'create');
$router->addRoute('owners/:ID', 'PUT',    'OwnerController',   'update');
$router->addRoute('owners/:ID', 'DELETE', 'OwnerController',   'delete');



// $router->addRoute('tareas',     'GET',    'TaskApiController', 'get'); # TaskApiController->get($params) EJEMPLO
// $router->addRoute('tareas',     'POST',   'TaskApiController', 'create');
// $router->addRoute('tareas/:ID', 'GET',    'TaskApiController', 'get');
// $router->addRoute('tareas/:ID', 'PUT',    'TaskApiController', 'update');
// $router->addRoute('tareas/:ID', 'DELETE', 'TaskApiController', 'delete');

// $router->addRoute('user/token', 'GET',    'UserApiController', 'getToken'); # UserApiController->getToken()

#               del htaccess resource=(), verbo con el que llamo GET/POST/PUT/etc
$router->route($_GET['resource'], $_SERVER['REQUEST_METHOD']);
