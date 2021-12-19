<?php

namespace Core\App;

class Response {
    public function Send($type, $data = '') {
        switch($type) {
            case 'json': 
                $this->Json($data);
            break;
            case 'rawjson':
                $this->RawJSON($data);
            break;
            case 'text': 
                $this->Text($data);
            break;
            case 404: 
                $this->notFound();
            break;
            default: 
                return 'No proper response type is set';
            break;
        }
    }
    public function Json($data) {
        header('Content-Type: application/json');
        echo json_encode($data, true);
    }
    public function Text($data) {
        header('Content-Type: text/plain');
        if(gettype($data) === 'string') {
            echo $data;
        }
        else {
            echo 'Invalid text input.';
        }
    }
    public function notFound() {
        header('HTTP/1.0 404 Not Found');
        header('Content-Type: application/json');
        echo json_encode([
            'status' => true,
            'message' => 'notfound'
        ]);
    }
}

?>