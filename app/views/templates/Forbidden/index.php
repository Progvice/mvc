<?php

namespace Core\App\Template;

use Core\App\Template;

class Forbidden extends Template
{
    public function load($values)
    {
        $this->collectStyle(__DIR__);
        $this->collectScript(__DIR__);

        $name = $values['name'] ?? '[name]';

        return <<<EOT
            <p>Template {$name} does not have permission to be loaded through API</p>
        EOT;
    }
}
