<?php

namespace Core\App\Models;
class Blocks extends MainModel {
    protected $rules;
    public function __construct() {
        $this->rules = [
            'primary_key' => 'id',
            'otsikko' => [
                'length' => 255,
                'type' => 'string',
                'required' => true,
                'unique' => false
            ],
            'kuvaus' => [
                'length' => 1020,
                'type' => 'string',
                'required' => true,
                'unique' => true
            ]
        ];
    }
}
?>