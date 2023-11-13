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

    public function create()
    {
    }
    public function get($params = [])
    {
        try {
            $this->userController->verifyUser();
            $field = !empty($_GET['sort']) ? $_GET['sort'] : '';
            $order = (!empty($_GET['order']) && strtoupper($_GET['order']) == 'D') ? 'DESC' : 'ASC';

            if (!empty($field)) {
                $pets = $this->model->getSortDataByField($field, $order);
                $this->view->responseWithData($pets, 200);
                return;
            }

            $pets = $this->model->getPets($order);
            $this->view->responseWithData($pets, 200);
        } catch (Exception $e) {
            $this->view->responseMessage("Error", 500);
        }
    }

    public function getOne($params = [])
    {
        try {
            $this->userController->verifyUser();
            $id = $params[':ID'];
            $pet = $this->model->getPetByID($id);
            $this->view->responseWithData($pet, 200);
        } catch (Exception $e) {
            $this->view->responseMessage("Error", 500);
        }
    }

    public function update($params = [])
    {
      try {
        $this->userController->verifyUser();
        $id = $params[':ID'];
        $body = $this->getData();
        if (empty($body) || $body->name == null || $body->age == null || $body->weight == null || $body->type == null || $body->ownerId == null) {
            $this->view->responseMessage('Falta información para actualizar el recurso', 400);
            return;
        }
        $name = $body->name;
        $age = $body->age;
        $weight = $body->weight;
        $type = $body->type;
        $idowner = $body->ownerId;
        $this->model->editPet($id, $name, $age, $weight, $type, $idowner);
        $this->view->responseMessage('Updated successfully', 200);
      } catch (Exception $e) {
        $this->view->responseMessage("Error", 500);
      }
    }

    public function delete($params = [])
    {
    }




    // public function getAllPets()
    // {
    //     $pets = $this->petModel->getPets();
    //     $owners = $this->ownerModel->getOwners();
    //     $this->view->showPets($pets, $owners);
    // }

    // public function getPetById($idpet)
    // {
    //     $pet = $this->petModel->getPetByID($idpet);
    //     $idowner = $pet->ID_DUENIO;
    //     $owner = $this->ownerModel->getOwnerByID($idowner);
    //     $this->view->showSpecificPet($pet, $owner);
    // }


    // public function getPetsByOwner($idOwner)
    // {
    //     $pets = $this->petModel->getPetsByOwner($idOwner);
    //     $owner = $this->ownerModel->getOwnerByID($idOwner);

    //     $this->view->showPetsByOwner($owner, $pets);
    // }

    // //------ ABM PETS ------ //

    // //funcion para mostrar la vista de creación de una mascota para un dueño
    // public function showCreatePet()
    // {
    //     $owners = $this->ownerModel->getOwners();
    //     $this->view->showCreatePet($owners);
    // }


    // //funcion para crear una pet
    // public function createPet()
    // {
    //     $name = $_POST['name'];
    //     $age = $_POST['age'];
    //     $weight = $_POST['weight'];
    //     $type = $_POST['type'];
    //     $idowner = $_POST['idowner'];
    //     $this->petModel->insertPet($name, $age, $weight, $type, $idowner);
    //     header("Location: " . BASE_URL . "/list-pets");
    // }

    // //funcion para mostrar la edición de una pet
    // public function showEditPet($idpet)
    // {
    //     $owners = $this->ownerModel->getOwners();
    //     $pet = $this->petModel->getPetByID($idpet);
    //     $this->view->showEditPet($pet, $owners);
    // }

    // //funcion para editar una pet
    // public function editPet($idpet)
    // {
    //     $name = $_POST['name'];
    //     $age = $_POST['age'];
    //     $weight = $_POST['weight'];
    //     $type = $_POST['type'];
    //     $idowner = $_POST['idowner'];
    //     $this->petModel->editPet($idpet, $name, $age, $weight, $type, $idowner);
    //     header("Location: " . BASE_URL . "/list-pets");
    // }

    // //funcion para eliminar una pet
    // public function deletePet($idpet)
    // {
    //     $this->petModel->deletePet($idpet);
    //     header("Location: " . BASE_URL . "/list-pets");
    // }
}
