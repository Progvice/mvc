<?php

use Core\App\Template;

class TestiLaatikko extends Template {
    public function load($values) {
        $this->collectStyle(__DIR__);
        return <<<EOT
            <h1>TestiLaatikko works!</h1>
        EOT;
    }
}
?>