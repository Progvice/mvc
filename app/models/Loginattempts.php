<?php
namespace Core\App\Models;
class Loginattempts extends MainModel {
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
            'tstamp' => [
                'length' => 10,
                'type' => 'int',
                'required' => true,
                'unique' => false
            ],
            'logincounter' => [
                'length' => 4,
                'type' => 'int',
                'required' => false,
                'unique' => false
            ],
            'ip' => [
                'length' => 15,
                'type' => 'string',
                'required' => true,
                'unique' => false
            ]
        ];
    }
}
?>