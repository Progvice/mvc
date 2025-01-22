<?php
namespace Core\App\Models;
class Guestrides extends MainModel {
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
            'address_from' => [
                'length' => 510,
                'type' => 'string',
                'required' => true,
                'unique' => false
            ],
            'address_to' => [
                'length' => 510,
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
                'length' => 510,
                'type' => 'string',
                'required' => true,
                'unique' => false
            ],
            'phonenumber' => [
                'length' => 20,
                'type' => 'string',
                'required' => true,
                'unique' => false
            ],
            'passengers' => [
                'length' => 2,
                'type' => 'int',
                'required' => true,
                'unique' => false
            ],
            'order_date' => [
                'required' => true,
                'unique' => false
            ],
            'order_time' => [
                'required' => true,
                'unique' => false
            ],
            'order_status' => [
                'length' => 255,
                'type' => 'string',
                'required' => true,
                'unique' => false
            ],
            'order_comment' => [
                'length' => 4080,
                'type' => 'string',
                'required' => false,
                'unique' => false
            ]
        ];
    }
}
?>