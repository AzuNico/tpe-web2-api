<?php
require_once './app/controllers/api.controller.php';
require_once './app/models/user.model.php';
require_once './app/helpers/auth.api.helper.php';

class UserController extends ApiController
{
    private $authHelper;

    public function __construct()
    {
        parent::__construct();
        $this->model = new UsersModel();
        $this->authHelper = new AuthHelper();
    }


    public function login()
    {
        try {
            $body = $this->getData();
            $this->validateRequestBody($body);

            $user = $this->model->getByUser($body->user);

            if (!$user) {
                $this->view->responseMessage('Usuario no encontrado', 404);
                die();
            }

            if (!password_verify($body->password, $user->PASSWORD)) {
                $this->view->responseMessage('Contraseña inválida', 401);
                die();
            }

            // objeto a array
            $user = (array) $user;

            $token = $this->authHelper->createToken($user);

            $data = ['token' => $token];

            $this->view->responseWithData($data, 200, 'Logueado correctamente');
        } catch (\Throwable $th) {
            $this->view->responseMessage('Error al loguear usuario', 500);
        }
    }


    public function create()
    {
        try {
            $body = $this->getData();
            $this->validateRequestBody($body);

            $this->verifyAlreadyExistsEmail($body->user);

            if (!$this->isEmailValidFormat($body->user)) {
                $this->view->responseMessage('Mail inválido', 400);
                die();
            }

            $email = $body->user;
            $password = $body->password;

            $this->model->registerUser($email, $password);
            $this->view->responseMessage('Se ha registrado correctamente', 201);
        } catch (\Throwable $th) {
            $this->view->responseMessage('Error al registrar usuario', 500);
        }
    }

    public function get($params = [])
    {
        $this->verifyUser();
        if (isset($params['id'])) {
            $user = $this->model->getUserById($params['id']);
            $this->view->responseWithData($user, 200);
        } else {
            $users = $this->model->getAllUsers();
            $this->view->responseWithData($users, 200);
        }
    }

    public function getOne($params = [])
    {
    }

    public function update()
    {
    }

    public function delete($params = [])
    {
    }



    public function verifyUser()
    {
        $user = $this->authHelper->currentUser();
        if (!$user) {
            $this->view->responseMessage('Unauthorized', 401);
            die();
        }
    }

    public function verifyAdmin()
    {
        $user = $this->authHelper->currentUser();
        if (!$user->admin) {
            $this->view->responseMessage('Unauthorized', 401);
            die();
        }
    }

    public function verifyAlreadyExistsEmail($email)
    {
        $user = $this->model->getByUser($email);
        if ($user) {
            $this->view->responseMessage('User already exists', 409);
            die();
        }
    }

    // Función que valida si el mail tiene el formato válido.
    public function isEmailValidFormat($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }
}
