<?php
namespace Core\App\Models;
class Perms extends MainModel {
    protected $rules;
    public function __construct() {
        $this->rules = [
            'perm_name' => [
                'length' => 255,
                'type' => 'string',
                'required' => true,
                'unique' => true
            ],
            'perm_priority' => [
                'length' => 2,
                'type' => 'tinyint',
                'required' => false,
                'unique' => false
            ],
            'admin_access' => [
                'length' => 1,
                'type' => 'tinyint',
                'required' => false,
                'unique' => false
            ],
            'pages_create' => [
                'length' => 1,
                'type' => 'tinyint',
                'required' => false,
                'unique' => false
            ],
            'pages_remove' => [
                'length' => 1,
                'type' => 'tinyint',
                'required' => false,
                'unique' => false
            ],
            'pages_update' => [
                'length' => 1,
                'type' => 'tinyint',
                'required' => false,
                'unique' => false
            ],
            'appointment_read' => [
                'length' => 1,
                'type' => 'tinyint',
                'required' => false,
                'unique' => false
            ],
            'appointment_create' => [
                'length' => 1,
                'type' => 'tinyint',
                'required' => false,
                'unique' => false
            ],
            'appointment_remove' => [
                'length' => 1,
                'type' => 'tinyint',
                'required' => false,
                'unique' => false
            ],
            'appointment_update' => [
                'length' => 1,
                'type' => 'tinyint',
                'required' => false,
                'unique' => false
            ],
            'appointment_other' => [
                'length' => 1,
                'type' => 'tinyint',
                'required' => false,
                'unique' => false
            ],
            'user_create' => [
                'length' => 1,
                'type' => 'tinyint',
                'required' => false,
                'unique' => false
            ],
            'user_remove' => [
                'length' => 1,
                'type' => 'tinyint',
                'required' => false,
                'unique' => false
            ],
            'user_update' => [
                'length' => 1,
                'type' => 'tinyint',
                'required' => false,
                'unique' => false
            ],
            'user_read' => [
                'length' => 1,
                'type' => 'tinyint',
                'required' => false,
                'unique' => false
            ],
            'user_other' => [
                'length' => 1,
                'type' => 'tinyint',
                'required' => false,
                'unique' => false
            ],
            'groups_create' => [
                'length' => 1,
                'type' => 'tinyint',
                'required' => false,
                'unique' => false
            ],
            'groups_remove' => [
                'length' => 1,
                'type' => 'tinyint',
                'required' => false,
                'unique' => false
            ],
            'groups_update' => [
                'length' => 1,
                'type' => 'tinyint',
                'required' => false,
                'unique' => false
            ],
            'groups_read' => [
                'length' => 1,
                'type' => 'tinyint',
                'required' => false,
                'unique' => false
            ]
        ];
    }
}
?>