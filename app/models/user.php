<?php

namespace Core\App\Models;
use \PDO;
class User {
    protected $primarykey = 'UID';
    protected $conn;
    public function __construct() {
        \plugin::load('db', true);
        $this->conn = \Core\App\DB::Connect();
    }
    public function Create($data) {
        $query = $this->conn->prepare('INSERT INTO jj_users (username, password, uperm) VALUES (:username, :password, :uperm)');
        $query->execute([
            ':username' => $data['username'],
            ':password' => $data['password'],
            ':uperm' => $data['uperm']
        ]);
    }
    public function Read($data) {

    }
    public function Update($data) {
        
    }
    public function Delete($data) {
        
    }
    public function ReadAll($data) {

    }
    public function Exists($data) {

    }
    
}

?>