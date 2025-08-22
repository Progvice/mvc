<?php

return [
    "url" => "/user",
    "controller" => "/user/index.php",
    "method" => "user",
    "name" => "user",
    "title" => "user",
    "params" => [
        "id" => [
            "method" => "getUserByID",
            "amount" => 2
        ]
    ]
];
