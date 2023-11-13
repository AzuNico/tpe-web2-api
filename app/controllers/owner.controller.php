<?php
require_once('app/models/owner.model.php');
require_once('app/controllers/user.controller.php');
require_once('app/controllers/api.controller.php');
require_once('app/helpers/adapter.api.helper.php');
require_once('app/models/pet.model.php');
class OwnerController extends ApiController
{
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
        $this->validateRequestBody($body);

        $name = $body->fullName;
        $email = $body->contactEmail;
        $phone = $body->phoneNumber;
        $this->model->insertOwner($name, $email, $phone);
        $this->view->responseMessage('Created successfully', 200);
    }
    public function get($params = [])
    {
        try {
            $field = !empty($_GET['sort']) ? $_GET['sort'] : '';
            $order = (!empty($_GET['order']) && strtoupper($_GET['order']) == 'D') ? 'DESC' : 'ASC';

            if (!empty($field)) {
                $owners = $this->model->getSortDataByField($field, $order);
                foreach ($owners as $owner) {
                    $ownerPets = $this->petModel->getPetsByOwner($owner->id);
                    $owner->pets = empty($ownerPets) ? [] : $ownerPets;
                }
                $this->view->responseWithData($owners, 200);
                return;
            }

            $owners = $this->model->getOwners($order);

            foreach ($owners as $owner) {
                $ownerPets = $this->petModel->getPetsByOwner($owner->id);
                $owner->pets = empty($ownerPets) ? [] : $ownerPets;
            }

            $this->view->responseWithData($owners, 200);
        } catch (\Throwable $th) {
            $this->view->responseStatus(500);
        }
    }

    public function getOne($params = [])
    {
        try {
            $idowner = $params[':ID'];

            $owner = $this->model->getOwnerByID($idowner);

            if ($owner) {

                $ownerPets = $this->petModel->getPetsByOwner($owner->id);
                $owner->pets = empty($ownerPets) ? [] : $ownerPets;

                $this->view->responseWithData($owner, 200);
            } else {
                $message = 'The owner with ID ' . $idowner . ' doesnt exist';
                $this->view->responseWithData(null, 404, $message);
            }
        } catch (\Throwable $th) {
            echo $th;
            $errorMessage = 'Error al obtener el owner con ID ' . $idowner;
            $this->view->responseMessage($errorMessage, 500);
        }
    }

    public function update()
    {
        try {
            $this->userController->verifyUser();
            $body = $this->getData();
            $isEditing = true;
            $this->validateRequestBody($body, $isEditing);

            $owner = $this->model->getOwnerByID($body->id);

            if (empty($owner)) {
                $this->view->responseMessage('El recurso que desea actualizar con id ' . $body->id . ' no existe', 404);
                return;
            }


            $idowner = $body->id;
            $name = $body->fullName;
            $email = $body->contactEmail;
            $phone = $body->phoneNumber;

            $this->model->editOwner($idowner, $name, $email, $phone);
            $this->view->responseWithData('Updated successfully', 200);
        } catch (\Throwable $th) {
            $errorMessage = "Error al actualizar, verificar los datos ingresados";
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
            $errorMessage = 'Error al eliminar el recurso con ID ' . $idowner;
            $this->view->responseMessage($errorMessage, 500);
        }
    }
}
