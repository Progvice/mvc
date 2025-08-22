<?php


return [
    "httpMethod" => "GET",
    "url" => "/personel/read",
    "controller" => "/personel/read/index.php",
    "actions" => "personel",
    "name" => "personel",
    "title" => "personel",

    "params" => [
        "q" => [
            "httpMethod" => "POST",
            "method" => "searchPersonel",
            "allowNoParams" => true
        ],
        "read" => [
            "httpMethod" => "GET",
            "method" => "personelById"
        ]
    ]

];
