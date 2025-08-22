<?php

return [
    "url" => "/user/update",
    "controller" => "/user/update/index.php",
    "actions" => "user",
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
