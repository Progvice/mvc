<?php
namespace Core\App\Models;
use Core\App\Models\Page;
class PageContent extends MainModel {
    protected $rules;
    public function __construct() {
        $this->rules = [      
            'belongsTo' => [
                Page::class
            ],   
            'template' => [
                'length' => 255,
                'type' => 'string',
                'required' => true,
                'unique' => false
            ],
            'data' => [
                'type' => 'json',
                'unique' => false,
                'required' => false
            ]
        ];
    }
}
