<?php

namespace Core\App\Template;

use Core\App\Template;

class SampleWithData extends Template
{
    public function load($values)
    {
        $this->collectStyle(__DIR__);
        $this->collectScript(__DIR__);

        $name = $values['name'] ?? '[undefined]';

        return <<<EOT
            <p>Greetings {$name}! This is template that is Sample with data. You can insert data to your templates and
            use templates dynamically with changing data. \$values variable can contain basically anything.</p>
        EOT;
    }
}
