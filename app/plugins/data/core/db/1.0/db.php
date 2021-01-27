<?php

namespace Core\App;
class DB {
    public static function Connect() {
        $conn = new \PDO(
            CONFIG['database']['dbtype'] .
            ':dbname=' . CONFIG['database']['dbname'] .
            ';host=' . CONFIG['database']['host'] . 
            ';charset=' . CONFIG['database']['charset'],
            CONFIG['database']['username'],
            CONFIG['database']['password']
        );
        return $conn;
    }
}

?>