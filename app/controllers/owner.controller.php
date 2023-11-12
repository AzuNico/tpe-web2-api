<?php
require_once('app/models/owner.model.php');
require_once('app/controllers/user.controller.php');
require_once('app/controllers/api.controller.php');
require_once('app/helpers/adapter.api.helper.php');
require_once('app/models/pet.model.php');
class OwnerController extends ApiController
{

    private $model;
    private $userController;

    private $petModel;

    public function __construct()
    {
        parent::__construct();
        $this->model = new OwnerModel();
        $this->userController = new UserController();
        $this->petModel = new PetModel();
    }

    public function create($params = [])
    {
        $this->userController->verifyUser();

        $body = $this->getData();
        if (empty($body) || $body->fullName == null || $body->contactEmail == null || $body->phoneNumber == null) {
            $this->view->responseMessage('Missing data', 400);
            return;
        }

        $name = $body->fullName;
        $email = $body->contactEmail;
        $phone = $body->phoneNumber;
        $this->model->insertOwner($name, $email, $phone);
        $this->view->responseMessage('Created successfully', 200);
    }
    public function get($params = [])
    {
        try {
            $this->userController->verifyUser();

            $field = (!empty($_GET['sort']) && $this->model->fieldExists(mapRequestField($_GET['sort']))) ? $_GET['sort'] : '';

            // si order es D, entonces es DESC, sino ASC
            $order = (!empty($_GET['order']) && strtoupper($_GET['order']) == 'D') ? 'DESC' : 'ASC';

            if (!empty($field)) {

                $field = mapRequestField($field);
                $owners = $this->model->orderBy($field, $order);
                $owners = mapOwners($owners);
                $this->view->responseWithData($owners, 200);
                return;
            }
            $owners = $this->model->getOwners($order);
            $owners = mapOwners($owners);
            $this->view->responseWithData($owners, 200);
        } catch (\Throwable $th) {
            $this->view->responseStatus(500);
        }
    }

    public function getOne($params = [])
    {
        try {
            $this->userController->verifyUser();

            $idowner = $params[':ID'];

            $owner = $this->model->getOwnerByID($idowner);

            if ($owner) {
                $owner = mapOwners($owner);
                $this->view->responseWithData($owner, 200);
            } else {
                $message = 'The owner with ID ' . $idowner . ' doesnt exist';
                $this->view->responseWithData(null, 404, $message);
            }
        } catch (\Throwable $th) {
            $errorMessage = 'Error al obtener el owner con ID ' . $idowner;
            $this->view->responseMessage($errorMessage, 500);
        }
    }

    public function update($params = [])
    {
        try {
            $this->userController->verifyUser();

            $idowner = $params[':ID'];

            $body = $this->getData();

            if ($body == null) {
                $this->view->responseMessage('Falta información para actualizar el recurso', 400);
                return;
            }
            if (empty($idowner) || $body->fullName == null || $body->contactEmail == null || $body->phoneNumber == null) {
                $this->view->responseMessage('Falta información para actualizar el recurso', 400);
                return;
            }

            if (!$this->model->getOwnerByID($idowner)) {
                $this->view->responseMessage('El recurso solicitado con id ' . $idowner . ' no existe', 404);
                return;
            }


            $name = $body->fullName;
            $email = $body->contactEmail;
            $phone = $body->phoneNumber;

            $this->model->editOwner($idowner, $name, $email, $phone);
            $this->view->responseWithData('Updated successfully', 200);
        } catch (\Throwable $th) {
            $errorMessage = 'Error al actualizar el owner con ID ' . $idowner;
            $this->view->responseMessage($errorMessage, 500);
        }
    }

    public function delete($params = [])
    {
        try {
            $this->userController->verifyUser();

            $idowner = $params[':ID'];

            if (empty($idowner)) {
                $this->view->responseMessage('Falta información para eliminar el recurso', 400);
                return;
            }

            $owner = $this->model->getOwnerByID($idowner);

            if (empty($owner)) {
                $this->view->responseMessage('El recurso solicitado con id ' . $idowner . ' no existe', 404);
                return;
            }

            if ($this->petModel->getPetsByOwner($idowner)) {
                $this->view->responseMessage('No se puede eliminar el owner con id ' . $idowner . ' porque tiene mascotas asociadas', 400);
                return;
            }

            $this->model->deleteOwner($idowner);
            $this->view->responseWithData('Deleted successfully', 200);
        } catch (\Throwable $th) {
            $errorMessage = 'Error al eliminar el owner con ID ' . $idowner;
            $this->view->responseMessage($errorMessage, 500);
        }
    }
}
