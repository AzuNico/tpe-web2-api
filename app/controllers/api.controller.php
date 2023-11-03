<?php
    require_once 'app/views/api.view.php';
    
    abstract class ApiController {
        protected $view;
        private $data;
        
        function __construct() {
            $this->view = new ApiView();
            $this->data = file_get_contents('php://input');
        }

        /**
         * Obtiene los datos enviados por el body de la petición
         */
        function getData() {
            return json_decode($this->data);
        }

         // CRUD
         public abstract function create(); // POST viaja por body
         public abstract function get($params = []); // GET viaja por URL
         public abstract function update(); // PUT viaja por body
         public abstract function delete($params = []); // DELETE viaja por URL
    }