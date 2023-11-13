<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once "config.php";
require_once "app/router/api.router.php";
require_once("app/controllers/owner.controller.php");
require_once("app/controllers/pet.controller.php");
require_once("app/controllers/user.controller.php");

$router = new Router();

#                 endpoint      verbo     controller           método
$router->addRoute("owners",     "GET",    "OwnerController",   "get");
$router->addRoute("owners/:ID", "GET",    "OwnerController",   "getOne");
$router->addRoute("owners",     "POST",   "OwnerController",   "create");
$router->addRoute("owners",     "PUT",    "OwnerController",   "update");
$router->addRoute("owners/:ID", "DELETE", "OwnerController",   "delete");

$router->addRoute("pets",       "GET",    "PetController",     "get");
$router->addRoute("pets/:ID",   "GET",    "PetController",     "getOne");
$router->addRoute("pets",       "PUT",    "PetController",     "update");


$router->addRoute("register",   "POST",   "UserController", "create");
$router->addRoute("login",      "POST",   "UserController", "login");


$router->route($_GET["resource"], $_SERVER["REQUEST_METHOD"]);
