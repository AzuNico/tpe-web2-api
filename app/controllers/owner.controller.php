<?php
require_once "./app/controllers/api.controller.php";
require_once './app/models/owner.model.php';
require_once './app/views/owner.view.php';
require_once './app/controllers/user.controller.php';
class OwnerController extends ApiController {

    private $model;
    private $userController;
    
    public function __construct(){
        parent::__construct();
        $this->model = new OwnerModel();
        $this->userController = new UserController();
    }

    public function create($params = []){
        $this->userController->verifyUser();

        $body = $this->getData(); 
        if($body->name == null || $body->email == null || $body->phone == null){
            $this->view->response('Missing data', 400);
            return;
        }

        $name = $body->name;
        $email = $body->email;
        $phone = $body->phone;
        $this->model->insertOwner($name, $email, $phone);
        $this->view->response('Created successfully', 200);
        
    }
    public function get($params = []){
        $this->userController->verifyUser();

        $owners = $this->model->getOwners();
        $this->view->response($owners, 200);
    }

    public function update(){
        $this->userController->verifyUser();

        $body = $this->getData(); 
        if($body->idowner == null || $body->name == null || $body->email == null || $body->phone == null){
            $this->view->response('Missing data', 400);
            return;
        }

        $idowner = $body->idowner;
        $name = $body->name;
        $email = $body->email;
        $phone = $body->phone;


        $this->model->editOwner($idowner, $name, $email, $phone);
        $this->view->response('Updated successfully', 200);
    }

    public function delete($params = []){
        $this->userController->verifyUser();

        $idowner = $params[':ID'];

        $owner = $this->model->getOwnerByID($idowner);
        
        if(empty($owner)) {
            $this->view->response('The owner with ID ' . $idowner . ' doesnt exist', 404);
            return;
        }

        $this->model->deleteOwner($idowner);
        $this->view->response('Deleted successfully', 200);
    }

}
