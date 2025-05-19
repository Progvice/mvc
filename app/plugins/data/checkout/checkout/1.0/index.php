<?php

class checkout {
    public function fetch($list) {
        foreach ($list as $c_o_file) {
            require __DIR__ . '/' . $c_o_file . '.php';
        }
    }
}

?>