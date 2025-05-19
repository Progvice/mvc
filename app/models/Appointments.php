<?php

namespace Core\App\Models;

class Appointments extends MainModel {
    protected $rules;
    public function __construct() {
        $this->rules = [
            'connections' => [
                'users' => [
                    'role' => 'owner',
                    'many' => false,
                    'required' => true,
                    'field' => 'uuid'
                ]
            ],
            'table' => 'Appointments',
            'uuid' => [
                'length' => 36,
                'type' => 'string',
                'required' => true,
                'unique' => true
            ],
            'userid' => [
                'length' => 36,
                'type' => 'string',
                'required' => true,
                'unique' => false
            ],
            'appointment_day' => [
                'type' => 'date',
                'required' => true,
                'unique' => false
            ],
            'slot_from' => [
                'type' => 'int',
                'required' => true,
                'unique' => false,
            ],
            'slot_to' => [
                'type' => 'int',
                'required' => true,
                'unique' => false,
            ],
            'title' => [
                'length' => 36,
                'type' => 'string',
                'required' => true,
                'unique' => false
            ],
            'description' => [
                'length' => 2040,
                'type' => 'string',
                'required' => false,
                'unique' => false
            ],
            'status' => [
                'length' => 255,
                'type' => 'string',
                'required' => true,
                'unique' => false
            ]
        ];
    }
}
?>