<?php
namespace Core\App\Models;
class Passwordreset extends MainModel {
    protected $rules;
    public function __construct() {
        $this->rules = [
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
            'expires' => [
                'length' => 12,
                'type' => 'int',
                'required' => true,
                'unique' => false
            ],
            'used' => [
                'length' => 1,
                'type' => 'int',
                'required' => false,
                'unique' => false
            ]
        ];
    }
}
?>