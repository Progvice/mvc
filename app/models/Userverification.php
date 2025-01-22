<?php
namespace Core\App\Models;
class Userverification extends MainModel {
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
                'length' => 18,
                'type' => 'int',
                'required' => true,
                'unique' => false
            ],
            'verified' => [
                'length' => 255,
                'type' => 'string',
                'required' => true,
                'unique' => false
            ],
        ];
    }
}
?>

