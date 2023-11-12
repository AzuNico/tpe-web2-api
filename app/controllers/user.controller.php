<?php
require_once './app/controllers/api.controller.php';
require_once './app/models/user.model.php';
require_once './app/helpers/auth.api.helper.php';

class UserController extends ApiController {
    

    private $model;
    private $authHelper;

    public function __construct() {
        $this->model = new UsersModel();
        $this->authHelper = new AuthHelper();
    }


    public function create(){

    }

    public function get($params = []){
        $this->verifyUser();
        if(isset($params['id'])){
            $user = $this->model->getUserById($params['id']);
            $this->view->responseWithData($user, 200);
        }else{
            $users = $this->model->getAllUsers();
            $this->view->responseWithData($users, 200);
        }
    }

    public function getOne($params = []){}

    public function update($params = []){}

    public function delete($params = []){}



    public function verifyUser() {
        //TODO: FALTA IMPLEMENTAR JWT
        // DESCOMENTAR ESTO CUANDO ESTE LISTO JWT:

        // $user = $this->authHelper->currentUser();
        // if(!$user) {
        //     $this->view->response('Unauthorized', 401);
        //     return;
        // }
    }



}