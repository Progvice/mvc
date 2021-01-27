# JJMVC

Welcome to JJMVC! This is MVC that i have created
for everyone to use. I have made myself tool that i can
use to make backend programming easier and now i want
to share it with everyone!

## Introduction

    JJMVC is simple MVC system that makes it easy to create new pages and use
    JJMVC's core tools to make things easier. JJMVC is easy to learn because
    of its simple structure. JJVMC is way to easier developing.

## Plugins

    JJMVC has its own plugin loader that handles loading of plugins. Plugins
    can be loaded in every part of request. Plugins can be loaded in other plugins too if needed!
    
    Plugin loader is lightweight and simple to use.

    Ex. plugin::load('plugin_name1, plugin_name2, etc...');

    Core elements

        Core elements are plugins that are made for basic functioning of JJMVC.
        
        Core plugins are stored at "/app/plugins/data/core"
        
        Every core plugin uses namespace that defines it is part of core.
        
        namespace Core\App\[component_name];
        
        
        
### Plugin structure
    
        When developer creates new plugin it is important to know how to create new plugin properly.
        
        /app/plugins/data                   This folder is for storing plugins.
        
        /app/plugins/json                   If plugin requires any additional data to be stored
                                            it should be stored here as JSON -file. Plugins can use
                                            database aswell.
                            
        /app/plugins/plugins.json           This file contains information about plugins.
                                            When plugins are loaded, plugin loader checks
                                            information from this specific file.

        /app/plugins/plugin_aliases.json    In this file developer can create aliases for plugins.
                                            Aliases make it easier to load plugins.
                                    
### Plugin request structure
    
        ```plugin::load('
            maker:name:version,
            maker:name:version
        ');
        ```
        -> Loader opens plugins.json -file and checks if plugin exists 
        -> Pass file path to loader
        -> request plugin if it is not already requested
        
### What about composer?

        There is folder for composer plugins at /app/plugins/composer

        User is able to load composer simply by calling "require COMPOSER" which
        then loads composer when needed. If developer feels like loading composer on every request
        developer can include/require it at "/app/controllers/controller.php" or at "/public/index.php"

        The reason why composer is supposed to load manually is that it loads less
        not necessary files when they are not needed.

        For necessary stuff JJMVC has it's own plugin loader which resembles composer but
        it is meant for manual loading. 
        
### How do i create plugins?

        You need to create your own JSON file so plugin loader is able to understand basic
        information what is required for adding new plugin to JJMVC.
        
        Required information:
        - Author
        - Plugin name
        - Plugin version
        
        Not required but recommended:

        - Requirements
            - PHP version
            - Other plugins
        
        In future with this information developer is able to upload plugin to jjmvc.net or to other 
        unofficial plugin libraries and it is easily accessible to other developers. 
        
        Below is simple example what plugin JSON should look like.
        
        {
            "author": {
                "pluginname": {
                    "1.0": {
                        "file": "index.php",
                    }
                }
            }
        }
        
        plugin::load('
            author:pluginname:1.0,
            otherauthor:pluginname:1.2
        ');
        
        It is recommended to load plugins this way. It makes syntax easier to read.
        
        Developers can use same names for plugins. Maker of the plugin and version numbering makes every plugin unique.
        Same developer cannot create two plugins with same name. Instead developers should create new version of their
        plugin.

        PLUGIN VERSION NUMBERING

        There are multiple ways to implement version numbering.
        
        EXAMPLE
        - "a1.0"
        - "b1.0"
        - "new_plugin1.0"

        It is still recommended to use basic version numbering to make things easier. 
        Developers don't like to load things like "author:pluginname:new_plugin1.0" because
        the loader has its own difficult syntax already.
        
        RECOMMENDED VERSION NUMBERING
        
        - "1.0"
        - "a1.0"
        - "b1.0"
        - "1.0.0"
        - "a1.0.0"
        - "b1.0.0"

        "a" indicates alpha and "b" indicates "beta"
        
        PLUGIN ALIASES

        Developer can set aliases for plugins. Aliases can be found at "/app/plugins/plugin_aliases.json"
        
        Aliases can be useful for plugins that are used often example for Core plugins. Some core plugins
        are already set as aliases by default. Aliases are enabled by default. If developer wants
        to disable aliases it is possible to set 2nd argument of Plugin Load to false.

        Alias is simple to set.

        EXAMPLE 
            "alias_name": "core:view:1.0"
        
        After that developer is able to load plugin as "alias_name"

        EXAMPLE
            plugin::load('alias_name');
        
        Developer can load plugins in normal syntax too at the same time while using aliases.

        EXAMPLE
        
            plugin::load('
                alias_name,
                author:pluginname:1.0
            ');
        
## Request path

    Request path is made simple. Every request goes through /public/index.php
    and it redirects request data to /app/Controller.php that redirects data straight
    to controller that URI is connected to. 
    
    /public/index.php -> /app/Controller.php -> /app/controller/[URI_PATH]/index.php
    
    URI path does not include $_GET data in URL so you are free to use $_GET in URL. 

    For now prettyURL is not possible and it would require huge changes. How ever
    in the future this feature will be included.
    
## File structure

    /app - App folder contains whole software logic in. Reason why software is built to App folder
    is that enduser is not able to gain access to .php -files. Every use of functionality is
    controlled by developer.
    
    /logs - Logs from web server
    
    /public - Webservers main folder that is accessible by user. User can use every resource
    in Public folder unless restricted by developer.
    
## Language

    Developer can load langauge translations from 2 different sources.
    
    JSON
        Developer should use JSON mostly for dynamic text translations.
        It is not useful in long run to use JSON for storing big chunks of text
        so avoid doing that. JSON file is loaded and set to "LANG" constant. Language is
        set automatically by reading "lang" cookie or by default setting (fi-fi).
        
    DATABASE
        Developer should use database for big text and text that possibly requires
        changes to it. In most cases text is loaded from database.
        
## Template loader

    W.I.P ( WORK IN PROGRESS )
    Developer can create templates that can be loaded easily with custom data
    from database. For now this feature is not fully designed.
    
    FOLDER
        /app/views/templates
            This folder is for storing templates.
            
        /app/plugins/data/core/1.0/temploader
            This folder contains logic for loading templates.
            
    HOW TO CREATE TEMPLATE?

        <?php
            namespace Core\App\Template;
            class exampletemplate extends \Core\App\Template {
            protected $template;
                
                public function __construct($values) {
                    $this->template = '
                        <div class="section-12">
                            <h1 class="bigtext">' . $values['title'] . '</h1>
                            <p>' . $values['description'] .'</p>
                        </div>';
                }
            }

        ?>
        
        Here is our example template. Class name of template should be same as filename.
        This way we can easily call for file name and class at the same time in
        template loader.
        
## JJCLI
        
        

