<?php
require_once 'app/views/api.view.php';

abstract class ApiController
{
    protected $view;

    protected $model;
    protected $data;

    function __construct()
    {
        $this->view = new ApiView();
        $this->data = file_get_contents('php://input');
    }

    /**
     * Obtiene los datos enviados por el body de la petición
     */
    function getData()
    {
        return json_decode($this->data);
    }

    // CRUD
    public abstract function create(); // POST viaja por body
    public abstract function get($params = []); // GET viaja por URL
    public abstract function getOne($params = []); // GET viaja por URL
    public abstract function update(); // PUT viaja por body
    public abstract function delete($params = []); // DELETE viaja por URL

    // Funciones auxiliares
    function validateRequestBody($body, $hasId = false)
    {
        try {

            if (empty($body)) {
                $this->view->responseMessage('El body no es válido', 400);
                die();
            }

            if ($hasId) {
                // CASO PUT
                if (empty($body->id)) {
                    $this->view->responseMessage('Debe enviar el id del recurso a actualizar', 400);
                    die();
                }                

            } else {
                // CASO POST
                if (!empty($body->id)) {
                    $this->view->responseMessage('No puede enviar el id del recurso a crear', 400);
                    die();
                }
            }

            // Convertir $body a un array
            $bodyArray = get_object_vars($body);

            // validar que los atributos del body sean los correctos según la dbFieldsMap
            $map = $this->model->getDbFieldsMap();
            $dbFieldsAmount = $hasId ? sizeof($map) : sizeof($map) - 1;
            $requestAttributesAmount = sizeof($bodyArray);

            if ($requestAttributesAmount < $dbFieldsAmount) {
                $this->view->responseMessage('Falta información para actualizar el recurso', 400);
                die();
            }

            if ($requestAttributesAmount > $dbFieldsAmount) {
                $this->view->responseMessage('Hay atributos que no pertenecen al recurso', 400);
                die();
            }

            foreach ($bodyArray as $key => $value) {
                if (!array_key_exists($key, $map)) {
                    $this->view->responseMessage('El atributo ' . $key . ' no existe', 400);
                    die();
                }
            }

          
        } catch (\Throwable $th) {
            //throw $th;
            echo $th;
        }
    }
}
