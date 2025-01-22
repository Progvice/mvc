<?php
namespace Core\App\Models;
class Customerreviews extends MainModel {
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
            'review' => [
                'length' => 2040,
                'type' => 'string',
                'required' => false,
                'unique' => false
            ],
            'review' => [
                'length' => 1,
                'type' => 'int',
                'required' => true,
                'unique' => false
            ]
        ];
    }
}
?>