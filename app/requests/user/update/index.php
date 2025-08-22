<?php

return [
    "url" => "/user/update",
    "controller" => "/user/update/index.php",
    "method" => "user",
    "name" => "user",
    "title" => "user",
    "params" => [
        "field" => [
            "method" => "changePassword"
        ],
        "restore" => [
            "method" => "createPasswordRestore"
        ],
        "passwordchange" => [
            "method" => "handlePasswordRestore",
            "view" => "passwordchange"
        ],
        "sendchange" => [
            "method" => "changePasswordWithCode"
        ]
    ]
];
