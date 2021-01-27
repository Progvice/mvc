<?php

namespace Core\App\Models;
use \PDO;
class Example {
    protected $primarykey = 'UID';
    protected $conn;
    public function __construct() {
        \plugin::load('db', true);
        $this->conn = \Core\App\DB::Connect();
    }
}

?>