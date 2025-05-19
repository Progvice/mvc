<?php
/*

    CLASS: plugin

    DESC: Plugin class is for loading plugins. You are able 
    to load and use different functions on the run without including
    files directly. Plugin class organizes your plugin files to JSON file
    by storing plugin name and path and possible dependencies. 

    Example:

    Plugin::load('plugin_name, plugin_name2');

    Developer is able to call for multiple plugins on same call without
    using load function multiple times. It also makes it easier to
    create cleaner code. 

    Plugin::load('
        plugin_name,
        plugin_name2,
        plugin_name3,
        plugin_name4
    ');
    
    JSON DATA

    "view" : [
                {
                    "name": "view",
                    "file": "view.php"
                }
            ],
            
    In this example we have basic JSON data in plugins.json -file.

    It stores name and filename. PLUGIN_PATH is set in index and you
    can change it if you feel like it. For now PLUGIN_PATH is at /app/plugins

    Plugins are stored to /app/plugins/data and JSON data that plugin
    requires is stored at /app/plugins/json. 
    
*/
class Plugin {
    protected static $json;
    /****
        *   @desc   Sets $json if it is not set already. Just use this function in beginning of every
        *           function.
        *
    *****/
    public static function json_load() {
        if (self::$json === null && empty(self::$json)) {
            self::$json = json_decode(file_get_contents(PLUGIN_PATH . '/plugins.json'));
        }
    }
    /****
        *   
        *   @name   load
        *   @param  string    
        *       @example 'author:plugin_name:version1, author2:plugin_name2:version2'
        *       @example2 'plugin_alias, plugin_alias2'
        *           NOTE: Aliases need to be set to plugin_aliases.json -file.
        *           /app/plugins/plugin_aliases.json
        *   @return void
    *****/
    public static function load($name_param) {
        self::json_load();
        $test_param = $name_param;
        $name_param = explode(',', $name_param);
        $aliases = json_decode(file_get_contents(PLUGIN_PATH . '/plugin_aliases.json'));
        foreach ($name_param as $name) {
            $name = trim($name);
            foreach ($aliases as $aliasname => $plugin) {
                if ($aliasname === $name) {
                    $name = $plugin;
                }
            }
            $plugin = explode(':', $name);
            if (count($plugin) < 3) {

            }
            $author = $plugin[0];
            $pname = $plugin[1];
            $version = $plugin[2];
            if (empty(self::$json->$author->$pname->$version)) {
                echo '<!-- Error: Could not load: ' . $author . ':' .$pname . ':' . $version . '. Plugin does not exist. -->';
                continue;
            }
            require_once PLUGIN_PATH . '/data/' . $author . '/' . $pname . '/' . $version . '/' . self::$json->$author->$pname->$version->file;
        }
    }
    /****    
        *
        *   @desc   This function downloads and sets every dependency for every plugin.
        *   @return void
        *   
    *****/
    public function loadall_dependencies() {
        self::json_load();
        foreach (self::$json as $plugin) {
            if (isset($plugin->dependencies)) {
                foreach ($plugin->dependencies as $dependency) {
                    $dname = $dependency->name;
                    if (empty(self::$json->$dname)) {
                        $plugin = [
                            "name" => $dependency->name,
                            "version" => $dependency->version,
                            "author" => $dependency->author
                        ];
                        self::Add($plugin);
                    }
                }
            }
        }
    }
    /****
        *
        *       @param array [name => plugin_name, version => plugin_version, author => plugin_author]
        *       @desc this function adds plugin and sets it to plugin.json and downloads all required 
        *       files.
        *       
        *       Changelog 31.12.2020
        *       
        *       I actually don't have this domain (jjmvc.net) active anymore so right now fetching plugins
        *       from the server is not possible. I had an idea that maybe i could try to make
        *       composer alike software of my own but for now i don't have the time to develope
        *       this. You can try and create your own plguin server if you feel like it. I don't have
        *       any guide for that at the moment.
        *
        *       --- WORK IN PROGRESS --- WORK IN PROGRESS --- WORK IN PROGRESS --- WORK IN PROGRESS
        *
    *****/
    public function add($plugin) {
        self::json_load();
        try {
            $file = file_get_contents('http://jjmvc.net/package?plugin=' . $plugin['name'] . '&v=' . $plugin['version'] . '&author=' . $plugin['author']);
        }
        catch(Exception $e) {
            if ($file === FALSE) {
                echo 'Failed to fetch plugin from package server.<br>';
            }
        }
        $file = json_decode($file);
    }
    /****   
        *
        *   @param  $plugin_name
        *   @example 
        *       remove("test:glafuski:latest")
        *   @return VOID
        *
    *****/
    public function remove($plugin_name) {
        self::json_load();
        
    }
    /****
        *   
        *   @desc   Saves current state of $json to plugins.json file.
        *
        *   @return Void
    *****/
    public function Save() {
        self::json_load();
        file_put_contents(PLUGIN_PATH . '/plugins.json', json_encode(self::$json, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
    }
    /****
        *
        * @param1  string requirement_name
        * @param2  mixed requirement_value
        * @desc    Check that requirements for plugin is set. 
        *          Plugin will stop if requirements are not met.
        *
    *****/
    public function requirement($requirement, $value) {
        
    }
}