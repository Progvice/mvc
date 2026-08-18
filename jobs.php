<?php

use Core\App\Models\MainModel;

class Jobs
{
    private int $completedJobs;
    public function __construct()
    {
        $this->completedJobs = "asd";
        require_once __DIR__ . '/app/controllers/loader.php';
        Plugin::load('models');
    }
}

new Jobs();
