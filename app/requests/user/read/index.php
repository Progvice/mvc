<?php

return [
    "url" => "/user/read",
    "controller" => "/user/read/index.php",
    "actions" => "user",
    "name" => "user",
    "title" => "user",
    "params" => [
        "id" => [
            "method" => "userById"
        ]
    ]
];
