<?php
namespace Core\App\Models;
class Example extends MainModel {
    protected $rules;
    public function __construct() {
        $this->rules = [
            'primary_key' => 'id',

            'example_column' => [
                'length' => 255,
                'type' => 'string',
                'required' => true,
                'unique' => false
            ]
        ];
    }
}
