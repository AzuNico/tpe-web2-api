<?php
require_once('app/controllers/api.controller.php');
require_once('app/models/pet.model.php');
require_once('app/models/owner.model.php');
require_once('app/controllers/user.controller.php');
require_once('app/helpers/adapter.api.helper.php');

class PetController extends ApiController
{
    private $ownerModel;

    private $userController;

    public function __construct()
    {
        parent::__construct();
        $this->model = new PetModel();
        $this->ownerModel = new OwnerModel();
        $this->userController = new UserController();
    }

    public function create($params = [])
    {
       try {
        $this->userController->verifyUser();

        $body = $this->getData();
        
        $this->validateRequestBody($body);
        

        $nombre = $body->name;
        $edad = $body->age;
        $peso = $body->weight;
        $tipo = $body->type;
        $id_duenio = $body->ownerId;
        
        $owner = $this->ownerModel->getOwnerByID($id_duenio);

        if (empty($owner)) {
            $this->view->responseMessage("El dueño con id " . $id_duenio . " no existe", 404);
            return;
        }

        $this->model->insertPet($nombre, $edad, $peso, $tipo, $id_duenio);

        $this->view->responseMessage("Created successfully",201);
       } catch (\Throwable $th) {
        //throw $th;
        $this->view->responseMessage("Error al crear recurso",500);
       }
    }
    public function get($params = [])
    {
        try {
            $field = !empty($_GET['sort']) ? $_GET['sort'] : '';
            $order = (!empty($_GET['order']) && strtoupper($_GET['order']) == 'D') ? 'DESC' : 'ASC';
            $filter = !empty($_GET['search']) ? $_GET['search'] : '';           //new filtro
            $filterOwnId = !empty($_GET['searchownid']) ? $_GET['searchownid'] : '';  

            if (!empty($field)) {
                $pets = $this->model->getSortDataByField($field, $order);
                $this->view->responseWithData($pets, 200);
                return;
            }

            if (!empty($filter)){
                $pets = $this->model->getFilterBy($filter);
                if (empty($pets)) {
                    $this->view->responseMessage("No se encontraron resultados.", 200);
                    return;
                }
                $this->view->responseWithData($pets,200);
                return;
            }

            if (!empty($filterOwnId)){
                $pets = $this->model->getPetsByOwner($filterOwnId);
                $this->view->responseWithData($pets,200);
                return;
            }

            $pets = $this->model->getPets($order);
            $this->view->responseWithData($pets, 200);
        } catch (Exception $e) {
            $this->view->responseMessage("Error al obtener el listado.", 500);
        }
    }

    public function getOne($params = [])
    {
        try {
            $id = $params[':ID'];
            $pet = $this->model->getPetByID($id);
            $this->view->responseWithData($pet, 200);
        } catch (Exception $e) {
            $this->view->responseMessage("Error al obtener recurso", 500);
        }
    }

    public function update()
    {
        try {
            $this->userController->verifyUser();
            $body = $this->getData();
            $isEditing = true;
            $this->validateRequestBody($body, $isEditing);

            $pet = $this->model->getPetByID($body->id);

            if(empty($pet)){
                $this->view->responseMessage('El recurso que desea actualizar con id ' . $body->id . ' no existe', 404);
                return;
            }

            $id = $body->id;
            $name = $body->name;
            $age = $body->age;
            $weight = $body->weight;
            $type = $body->type;
            $idowner = $body->ownerId;
            $this->model->editPet($id, $name, $age, $weight, $type, $idowner);
            $this->view->responseMessage('Updated successfully', 200);
        } catch (Exception $e) {
            $this->view->responseMessage("Error al actualizar, verificar los datos ingresados", 500);
        }
    }

    public function delete($params = [])
    {
        try {
            $this->userController->verifyUser();
            $id = $params[':ID'];
            $pet = $this->model->getPetByID($id);

            if(empty($pet)){
                $this->view->responseMessage('El recurso que desea eliminar con id ' . $id . ' no existe', 404);
                return;
            }

            $this->model->deletePet($id);
            $this->view->responseMessage('Deleted successfully', 200);
        } catch (Exception $e) {
            $this->view->responseMessage("Error al eliminar recurso", 500);
        }
    }

}
