<?php

use Core\App\Models\Streetaddress;
use Core\App\Models\Personel;

return [
    'order' => [
        Personel::class,
        Streetaddress::class
    ]
];
