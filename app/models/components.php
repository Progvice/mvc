<?php

namespace Core\App\Models;
use \PDO;
class Components extends MainModel {
    protected $conn;
    public function __construct() {
        \plugin::load('db', true);
        $this->conn = \Core\App\DB::Connect();
    }
    public function Create($data) {
        /* Tarkistetaan onko komponentin nimi käytössä */
        $k_query = $this->conn->prepare('SELECT * FROM Components WHERE name=?');
        $k_query->execute(array($data['name']));
        if(count($k_query->fetchAll()) > 0) {
            echo 'Component with this name exists.';
            $exists = true;
        }
        if(isset($exists)) {
            return;
        }
        /* Lisätään komponentti tarkistusten jälkeen */
        $query = $this->conn->prepare('INSERT INTO components (name) VALUES (:name)');
        $query->execute(
            [
                ':name' => $data['name'],
            ]
        );
    }
    public function Read($data) {
        $query = $this->conn->prepare('SELECT * FROM components WHERE name = :name');
        $query->execute([':name' => $data]);
        $result = $query->fetch(PDO::FETCH_ASSOC);
        return $result;
    }
    public function Update() {
        
    }
    public function Delete() {
        
    }    
}

?>