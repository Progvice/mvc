<?php

namespace Core\App\Models;

use Core\App\Models\Personel;

class Streetaddress extends MainModel
{
    protected $rules;
    public function __construct()
    {
        $this->rules = [
            'primary_key' => 'id',
            'belongsTo' => [Personel::class],

            'created_at' => true,
            'modified_at' => true,

            'streetaddress' => [
                'length' => 255,
                'type' => 'string',
                'required' => true,
                'unique' => false
            ],
            'streetnumber' => [
                'length' => 5,
                'type' => 'number',
                'required' => true,
                'unique' => false
            ],
            'apartment' => [
                'length' => 10,
                'type' => 'string',
                'required' => false,
                'unique' => false
            ],
            'city' => [
                'length' => 255,
                'type' => 'string',
                'required' => true,
                'unique' => false
            ],
            'country' => [
                'length' => 2,
                'type' => 'fstring',
                'required' => true,
                'unique' => false
            ],
        ];
    }
}
