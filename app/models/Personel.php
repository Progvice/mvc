<?php

namespace Core\App\Models;

use Core\App\Models\Streetaddress;
use Core\App\Models\Rides;

class Personel extends MainModel
{
    protected $rules;
    public function __construct()
    {
        $this->rules = [
            'primary_key' => 'id',
            'hasMany' => [Streetaddress::class, Rides::class],

            'firstname' => [
                'length' => 155,
                'type' => 'string',
                'required' => true,
                'unique' => false
            ],
            'lastname' => [
                'length' => 155,
                'type' => 'string',
                'required' => true,
                'unique' => false
            ],
            'email' => [
                'length' => 155,
                'type' => 'string',
                'required' => true,
                'unique' => true
            ],
            'phonenumber' => [
                'length' => 22,
                'type' => 'string',
                'required' => true,
                'unique' => true
            ],
            'birthday' => [
                'type' => 'date',
                'required' => true,
                'unique' => false
            ],
        ];
    }
}
