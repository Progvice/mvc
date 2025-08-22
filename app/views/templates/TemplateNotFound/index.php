<?php

namespace Core\App\Template;

use Core\App\Template;

class TemplateNotFound extends Template
{
    public function load($values)
    {
        $this->collectStyle(__DIR__);
        $this->collectScript(__DIR__);

        $templateName = $values;

        return <<<EOT
            <p>Template "{$templateName}" not found</p>
        EOT;
    }
}
