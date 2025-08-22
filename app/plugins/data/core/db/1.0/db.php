<?php

namespace Core\App;

use \PDO;

class DB
{
    public static function Connect()
    {
        $conn = new PDO(
            CONFIG['database']['dbtype'] .
                ':dbname=' . CONFIG['database']['dbname'] .
                ';host=' . CONFIG['database']['host'] .
                ';charset=' . CONFIG['database']['charset'],
            CONFIG['database']['username'],
            CONFIG['database']['password']
        );
        if (isset(CONFIG['database']['nativevalues']) && CONFIG['database']['nativevalues'] === true) {
            $conn->setAttribute(PDO::ATTR_STRINGIFY_FETCHES, false);
            $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        }
        return $conn;
    }
}
