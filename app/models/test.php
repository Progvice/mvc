<?php

namespace Core\App\Models;
use \PDO;
class Test {
    protected $conn;
    public function __construct() {
        \plugin::load('db');
        $this->conn = \Core\App\DB::Connect();
    }
    public function Create($data) {

    }
    public function Read($data) {

    }
    public function Update($data) {
        
    }
    public function Delete($data) {
        
    }
    public function ReadAll() {

    }
    public function Exists($data) {

    }
    
}

?>