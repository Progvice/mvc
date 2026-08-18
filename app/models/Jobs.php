<?php
namespace Core\App\Models;

class Jobs extends MainModel {
    protected $rules;
    public function __construct() {
        $this->rules = [
            'primary_key' => 'id',
            'name' => [
                'length' => 256,
                'type' => 'string',
                'required' => true,
                'unique' => false
            ],
            'action' => [
                'length' => 254,
                'type' => 'string',
                'required' => true,
                'unique' => false
            ],
            'status' => [
                'type' => 'string'
            ],
            'jobtype' => [
                'type' => 'string'
            ]
        ];
    }
}
