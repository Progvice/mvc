<?php

namespace Core\App;

class Request {
    public function CheckType($reqtype) {
        if ($_SERVER['REQUEST_METHOD'] !== $reqtype) {
            return [
                'status' => false,
                'message' => 'Request type is not valid.'
            ];
        }
        return [
            'status' => true,
            'message' => 'OK'
        ];
    }
}

?>