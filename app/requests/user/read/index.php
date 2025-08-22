<?php

return [
    "url" => "/user/read",
    "controller" => "/user/read/index.php",
    "method" => "user",
    "name" => "user",
    "title" => "user",
    "params" => [
        "id" => [
            "method" => "userById"
        ]
    ]
];
