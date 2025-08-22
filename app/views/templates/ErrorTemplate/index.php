<?php

namespace Core\App\Template;

use Core\App\Template;

class ErrorTemplate extends Template
{
    public function load($values)
    {
        $this->collectStyle(__DIR__);
        $this->collectScript(__DIR__);

        $name = $values;

        return <<<EOT
            <p>There was error loading template {$name}</p>
        EOT;
    }
}
