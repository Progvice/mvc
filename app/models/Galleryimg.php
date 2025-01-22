<?php
namespace Core\App\Models;
class Galleryimg extends MainModel {
    protected $rules;
    public function __construct() {
        $this->rules = [
            'uuid' => [
                'length' => 36,
                'type' => 'string',
                'required' => true,
                'unique' => true
            ],
            'gname' => [
                'length' => 255,
                'type' => 'string',
                'required' => true,
                'unique' => false
            ],
            'imgname' => [
                'length' => 255,
                'type' => 'string',
                'required' => false,
                'unique' => false
            ],
            'fname' => [
                'length' => 255,
                'type' => 'string',
                'required' => true,
                'unique' => false
            ],
            'imgpath' => [
                'length' => 255,
                'type' => 'string',
                'required' => true,
                'unique' => false
            ],
            'description' => [
                'length' => 255,
                'type' => 'string',
                'required' => false,
                'unique' => false    
            ]
        ];
    }
}
?>