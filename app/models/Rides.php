<?php
namespace Core\App\Models;
class Rides extends MainModel {
    protected $rules;
    public function __construct() {
        $this->rules = [
            'belongsTo' => [
                Personel::class
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
            'pickuptime' => [
                'required' => true,
                'unique' => false,
                'type' => 'string',
                'length' => 255
            ],
            'homeaddr' => [
                'length' => 254,
                'type' => 'string',
                'required' => true,
                'unique' => false
            ],
            'zipcode' => [
                'length' => 5,
                'type' => 'string',
                'required' => true,
                'unique' => false
            ],
            'city' => [
                'length' => 255,
                'type' => 'string',
                'required' => true,
                'unique' => false
            ],
            'apartment' => [
                'length' => 10,
                'type' => 'string',
                'required' => false,
                'unique' => false
            ],
        ];
    }
}
?>