<?php
namespace Core\App\Models;
use Core\App\Models\PageContent;
class Page extends MainModel {
    protected $rules;
    public function __construct() {
        $this->rules = [      
            'primary_key' => 'id',
            'hasMany' => [PageContent::class],   
            'title' => [
                'length' => 255,
                'type' => 'string',
                'required' => true,
                'unique' => false
            ],
            'uri' => [
                'length' => 255,
                'type' => 'string',
                'required' => true,
                'unique' => true
            ]
        ];
    }
}
