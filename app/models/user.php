<?php
namespace Core\App\Models;
class User extends MainModel {
    protected $rules;
    public function __construct() {
        $this->rules = [
            'primary_key' => 'id',
            'username' => [
                'length' => 255,
                'type' => 'string',
                'required' => true,
                'unique' => true
            ],
            'password' => [
                'length' => 255,
                'type' => 'string',
                'required' => true,
                'unique' => false
            ]
        ];
    }
}
?>