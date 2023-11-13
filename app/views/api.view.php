<?php
class ApiView
{

    // Función que retorna la estructura JSON del response
    public function dataToResponse($data, $status = 200, $message = null)
    {
        if (empty($data)) {
            $response = [
                'status' => $status,
                'message' => $message
            ];
            return json_encode($response);
        }

        $response = [
            'data' => $data,
            'status' => $status,
            'message' => $message
        ];
        return json_encode($response);
    }
    public function responseWithData($data, $status = 200, $message = null)
    {
        header('Content-type: application/json');
        header('HTTP/1.1 ' . $status . " " . $this->_requestStatus($status));
        if ($message) {
            echo $this->dataToResponse($data, $status, $message);
            return;
        } else {
            echo $this->dataToResponse($data, $status, $this->_requestStatus($status));
            return;
        }
    }

    public function responseMessage($message, $status = 200)
    {
        $this->responseWithData([], $status, $message);
    }
    public function responseStatus($status = 200)
    {
        $this->responseWithData(null, $status, $this->_requestStatus($status));
    }

    private function _requestStatus($code)
    {
        $status = array(
            200 => "OK",
            201 => "Created",
            404 => "Not found",
            400 => "Bad request",
            500 => "Internal server error",
        );
        return (isset($status[$code])) ? $status[$code] : $status[500];
    }
}
