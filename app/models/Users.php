<?php
namespace Core\App\Models;
class Users extends MainModel {
    protected $rules;
    public function __construct() {
        $this->rules = [
            'connections' => [
                'appointments' => [
                    'role' => 'servant',
                    'many' => true,
                    'required' => false,
                    'field' => 'userid'
                ],
                "Guestrides" => [
                    'role' => 'servant',
                    'many' => true,
                    'required' => false,
                    'field' => 'userid'
                ]
            ],
            'table' => 'Users',
            'primarykey' => 'uuid',
            'uuid' => [
                'length' => 36,
                'type' => 'string',
                'required' => true,
                'unique' => true
            ],
            'permgroup' => [
                'length' => 255,
                'type' => 'string',
                'required' => true,
                'unique' => false
            ],
            'firstname' => [
                'length' => 255,
                'type' => 'string',
                'required' => true,
                'unique' => false
            ],
            'lastname' => [
                'length' => 255,
                'type' => 'string',
                'required' => true,
                'unique' => false
            ],
            'email' => [
                'length' => 255,
                'type' => 'string',
                'required' => true,
                'unique' => true
            ],
            'phonenumber' => [
                'length' => 255,
                'type' => 'string',
                'required' => true,
                'unique' => true
            ],
            'password' => [
                'length' => 255,
                'type' => 'string',
                'required' => false,
                'unique' => false
            ],
            'verified' => [
                'length' => 1,
                'type' => 'int',
                'required' => false,
                'unique' => false
            ]
        ];
    }
}
?>