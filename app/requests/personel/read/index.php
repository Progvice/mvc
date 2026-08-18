<?php
return [
    "httpMethod" => "GET",
    "url" => "/personel/read",
    "controller" => "/personel/read/index.php",
    "method" => "personelRead",
    "name" => "PersonelRead",
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
