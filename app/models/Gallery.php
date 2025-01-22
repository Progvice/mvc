<?php
namespace Core\App\Models;
class Gallery extends MainModel {
    protected $rules;
    public function __construct() {
        $this->rules = [
            'gname' => [
                'length' => 255,
                'type' => 'string',
                'required' => true,
                'unique' => true
            ]
        ];
    }
}
?>