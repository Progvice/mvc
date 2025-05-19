<?php

namespace Core\App;

class Lang {
    public function __construct() {
        if ($_SESSION['lang'] === NULL) {
            $_SESSION['lang'] = 'fi-fi';
        }
    }
    /*
     *  @name   FetchDB
     *  @param  $values @mixed - string or array
     *  @return @mixed - string or array
     *
     *  @example
     *  
     *  FetchDB('langobj_name')
     *
     *  OR
     *
     *  FetchDB(['langobj_name1', 'langobj_name2'])
     *
     */
    
    public function FetchDB($values) {
        if (is_array($values)) {
            
        }  
    }
    /*
     *  @name   FetchJSON
     *  @param  $values @mixed - string or array
     *  @return @mixed - string or array
     *
     *  @example
     *  
     *  FetchJSON('langobj_name') 
     *  
     *  OR
     *
     *  FetchJSON(['langobj_name1', 'langobj_name2'])  
     *
     */
    public function FetchJSON($values) {
        if (is_array($values)) {
            
        }
    }
    /*
     *  @name   InsertJSON
     *  @param  $values @mixed - string or array
     *  @return @mixed - string or array
     *
     *  @example
     *  
     *  InsertJSON('langobj_name') 
     *  
     *  OR
     *
     *  InsertJSON(['langobj_name1', 'langobj_name2'])  
     *
     */    
    public function InsertJSON($values) {
    /*
     *  @name   InsertDB
     *  @param  $values @mixed - string or array
     *  @return @mixed - string or array
     *
     *  @example
     *  
     *  InsertDB('langobj_name') 
     *  
     *  OR
     *
     *  InsertDB(['langobj_name1', 'langobj_name2'])  
     *
     */        
    }
    public function InsertDB($values) {
        
    }
}
?>
