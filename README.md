# **JJMVC**

## **Introduction**

Welcome to JJMVC! This is MVC that i have created
for everyone to use. I decided to create this tool for myself
but now i want to share it with you all! Let's make this
great tool together!

1. [Request Path](#request-path)
2. [Plugins](#plugins)
   - [Core Plugins](#core-plugins)
   - [Plugin Structure](#plugin-structure)
   - [Plugin Request Structure](#plugin-request-structure)
   - [What About Composer](#what-about-composer)
   - [How to Create Plugins](#how-to-create-plugins)
   - [Plugin Aliases](#plugin-aliases)
3. [File Structure](#file-structure)
   - [App Folder (`/app`)](#app)
   - [Logs Folder (`/logs`)](#logs)
   - [Public Folder (`/public`)](#public)
4. [Parameters in URI](#parameters-in-uri)
5. [Language](#language)
6. [Template Loader](#template-loader)
   - [Template Folder](#folder)
   - [How to Create a Template](#how-to-create-template)
7. [Models](#models)
   - [How to Create a Model](#how-to-create-model)
   - [Model Structure](#model-structure)
   - [Using Models](#how-to-use-models)
   - [Advanced Select Methods](#example---advanced-select-methods)
   - [Password Hashing](#password-hashing)
8. [Donate Me](#donate-me)

## **Request path**

Request path is made simple. Every request goes through /public/index.php
and it redirects request data to /app/Controller.php that redirects data straight
to controller that URI is connected to.

/public/index.php -> /app/Controller.php -> /app/controller/[URI_PATH]/index.php

URI path does not include $\_GET data in URL so you are free to use $\_GET in URL.

For now prettyURL is not possible and it would require huge changes. How ever
in the future this feature will be included.

## **File structure**

### **/app**

App folder contains whole software logic in. Reason why software is built to App folder is that user does not have access to any critical part of the software. It makes this software one step more secure. App folder has 5 different main folders in it and now this documentation is going to explain every folder.

**/app/controllers**

Controllers folder contains controllers that handle requests. Every URI has its own controller.

**/app/models/**

Models folder is for database models. Models are explained later in this document.

**/app/plugins**

Plugin folder contains plugins that developer can load in any part of request. More about plugins structure later in this document.

**/app/requests**

This folder contains request data for every URI. Here developer can create request settings for URI. Example developer can set that what controller to load. Default way to load controller is to use URI. For now request files needs to be created manually.

**EXAMPLE**

    /controllers/products/index.php
    /requests/products/index.json

### **/logs**

Webserver logs come here

### **/public**

Webservers main folder that is accessible by user. User can use every resource in Public folder unless restricted by developer.

## **Plugins**

JJMVC has its own plugin loader that handles loading of plugins. Plugins
can be loaded in every part of request. Plugins can be loaded in other plugins too if needed!

Plugin loader is lightweight and simple to use.

    plugin::load('plugin_name1, plugin_name2, etc...');

### **Core plugins**

Core plugins are plugins that are made for basic functioning of JJMVC. Core plugins are stored at:

    /app/plugins/data/core

Every core plugin uses namespace that defines that it is part of the core.

### **Plugin structure**

When developer creates new plugins it is important to know how to create new plugin properly.

This folder is for storing plugin logic.

    /app/plugins/data

If plugin requires any additional data (ex. config files) to be stored it should be stored here:

    /app/plugins/json

This file contains information about plugins. When plugins are loaded, plugin loader checks information from this specific file:

    /app/plugins/plugins.json

In this file developer can create aliases for plugins. Aliases make it easier to load plugins.

    /app/plugins/plugin_aliases.json

### **Plugin request structure**

    plugin::load('
        maker:name:version,
        maker:name:version
    ');

    -> Loader opens plugins.json -file and checks if plugin exists
    -> Pass file path to loader
    -> request plugin if it is not already requested

### **What about composer**

Composer folder

    /app/plugins/composer

User is able to load composer simply by calling "require COMPOSER" which then loads composer when needed.

If developer feels like loading composer on every request developer can include/require it at "/app/controllers/controller.php" or at "/public/index.php"

The reason why composer is supposed to load manually is that it loads less files that are not necessary. For necessary stuff JJMVC has it's own plugin loader which resembles composer but it is meant for manual loading.

### **How to create plugins**

At the moment developer can add new plugins only manually but in future JJMVC does have feature that makes adding new plugins easier.

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

Data where all plugins are listed can be found in:

    /app/plugins/plugins.json

This is what plugin JSON should look like:

    {
        "author": {
            "pluginname": {
                "1.0": {
                    "file": "index.php",
                }
            }
        }
    }

How plugins are stored with information that is set above:

    /app/plugins/data/[author]/[plugin_name]/[1.0]/index.php

And then how to load plugins:

    plugin::load('
        author:pluginname:1.0,
        otherauthor:pluginname:1.2
    ');

It is recommended to load plugins this way. It makes syntax easier to read.

Developers can use same names for plugins. Maker of the plugin and version numbering makes every plugin unique.
Same developer cannot create two plugins with same name. Instead developers should create new version of their plugin.

PLUGIN VERSION NUMBERING

There are multiple ways to implement version numbering.

**EXAMPLE**

- "a1.0"
- "b1.0"
- "new_plugin1.0"

It is still recommended to use basic version numbering to make things easier.
Developers don't like to load things like "author:pluginname:new_plugin1.0" because the loader has its own difficult syntax already.

RECOMMENDED VERSION NUMBERING

- "1.0"
- "a1.0"
- "b1.0"
- "1.0.0"
- "a1.0.0"
- "b1.0.0"

"a" indicates alpha and "b" indicates "beta"

### **PLUGIN ALIASES**

Developer can set aliases for plugins. Aliases can be found here:

    /app/plugins/plugin_aliases.json

Aliases can be useful for plugins that are used often for example core plugins. Some core plugins have aliases by default.
Aliases are enabled by default. If developer wants
to disable aliases it is possible to set 2nd argument of Plugin Load to false. Aliases are simple to set.

**EXAMPLE**

    "alias_name": "core:view:1.0"

After that developer is able to load plugin as "alias_name"

**EXAMPLE**

    plugin::load('alias_name');

Aliases and normal syntax can used at the same time.

**EXAMPLE**

    plugin::load('
        alias_name,
        author:pluginname:1.0
    ');

## **Request path**

Request path is made simple. Every request goes through /public/index.php
and it redirects request data to /app/Controller.php that redirects data straight
to controller that URI is connected to.

/public/index.php -> /app/Controller.php -> /app/controller/[URI_PATH]/index.php

URI path does not include $\_GET data in URL so you are free to use $\_GET in URL.

## **Parameters in URI**

I have created my own way of handling parameters in URL. Developer can use params in
URI when it is enabled in URIs request json. Developer has to define parameter entry point
to request JSON. For example:

    "params": {
        "id": {
            "method": "getUserByID",
            "amount": 2
        }
    }

You can find this information at "app/requests/user/index.json".

Now we have set parameter to "/user" URI. We have defined that "id" is our
entry point. For our entry point we need to define method. Method should be
written in the same controller that parameter is connected to.

Lets say that we send request to this URI "/user/id/203858" with method below:

    public function getUserByID() {
        plugin::load('response');
        $response = new Core\App\Response;
        $response->Send('json', [
            'status' => true,
            'message' => 'Parameters are working',
            'params' => $this->params
        ]);
    }

We should get output like this:

    {
        "status": true,
        "message": "Parameters are working",
        "params": [
            "203858"
        ]
    }

How about if we want to have second parameter? Simply we can insert more data to URI.

    /user/id/203858/9589493

Now we should have output like this:

    {
        "status": true,
        "message": "Parameters are working",
        "params": [
            "203858",
            "9589493"
        ]
    }

Developer can access this parameter data by using "$this->params". This variable
has array in it that contains every parameter. REMEMBER TO FILTER THIS DATA! User is able to
send data through these parameters. It is very important to filter user input.

## **Language**

Developer can load langauge translations from 2 different sources.

JSON
Developer should use JSON mostly for dynamic text translations.
It is not useful in long run to use JSON for storing big chunks of text
so avoid doing that. JSON file is loaded and set to "LANG" constant. Language is
set automatically by reading "lang" cookie or by default setting (fi-fi).

DATABASE
Developer should use database for big text and text that possibly requires
changes to it. In most cases text is loaded from database.

## **Template loader**

Developer can create templates that can be loaded easily with custom data
from JSON files. For now this feature is in beta state at the moment.

### **FOLDER**

This folder is used for storing templates.

    /app/views/templates

### **How to create template**

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

## **Models**

Models give developer easy way to control flow of data to database. Models are very easy to create when using JJCLI commandline commands.

### **How to create model**

You can simply create model by using JJCLI. Let's start by going to our approot folder with terminal/CMD/etc. Approot folder is folder that contains all 3 main folders (app, public, logs)

Write this command to create new model:

    php jjcli.php createmodel MODELNAME

Replace MODELNAME with table name you use in database. Now we have created basic model and we can start editing it.

### **Model structure**

    $this->rules = [
        'primary_key' => 'id',
        'example_column' => [
            'length' => 255,
            'type' => 'string',
            'required' => true,
            'unique' => false
        ]
    ];

This is what our example model has in it. Now I am going to explain what all this means.

- Primary key -> This tells MainModel that this is our tables primary key.
- Example column -> This contains every setting for column named "example_column"
  - length -> Sets maximum length that column can store in database
  - type -> Sets datatype that column should contain
  - required -> If set to true this data can't be empty otherwise it will not be send.
  - unique -> Check that if this data exists already.

So basically developer can define what data should be and how should it be. MainModel checks that every piece of data is set correctly before sending it to database. None of these settings are not required to set. Every setting is optional but makes it easier for developer to maintain data integrity with database.

**EXAMPLE**

    $this->rules = [
        'primary_key' => 'id',
        'username' => [
            'length' => 255,
            'type' => 'string',
            'required' => true,
            'unique' => true
        ],
        'password' => [
            'length' => 255,
            'type' => 'string',
            'required' => true,
            'unique' => false
        ],
        'birthday' => [
            // We do not need any length for birthday as we are only accepting date.
            'type' => 'date',
            'required' => false,
            'unique' => false
        ]
    ]

### **How to use models**

**EXAMPLE - INSERT**

    $model = new Core\App\Models\MainModel;
    $model->CallModel('user');

    $data = [
        'username' => 'myusername,
        'password' => 'password_example',
        'birthday' => 'month, day, year'
    ];
    $model->Insert($data);

**EXAMPLE - SELECT**

    $model = new Core\App\Models\MainModel;
    $model->CallModel('user');
    $data = $model->Select([
        'values' => [
            'normal' => [
                'username' => 'Foobar'
            ]
        ]
    ]);

    /* $data has information about user whose username is Foobar.*/

This is just basic example of how to use Select function.
However there are still other methods to search data. Here is list
of those methods

**EXAMPLE - Advanced Select methods**


    $model->Select([
        'values' => [
            'normal' => [
                'username' => 'Foobar' // FIELD = Foobar
            ]
            'contains' => [
                'username' => 'oba' // FIELD = %oba%
            ],
            'starts' => [
                'username' => 'Foo' // FIELD = Foo%
            ],
            'ends' => [
                'username' => 'bar' // FIELD = %bar
            ],
            'bigger' => [
                'balance' => '10' // FIELD > 10
            ],
            'smaller' => [
                'balance' => '10' // FIELD < 10
            ]
        ]
    ])

Here you have all the Select methods that you can use. These methods can be chained together on the same Select request.

### Password hashing ###

Password hashing is done automatically when inserting 'password'
field with data to $model->Insert or $model->Update. This is added
security feature. However this can be turned off by setting additional parameter to Insert or Update function

    $data = ['field' => 'data', 'password' => 'Foobar123!'];

    $model->Insert($data, ['PASSWORD_NO_HASH' => true]);



    


## Donate me

You can donate me with cryptocurrencies.

**ETH:** 0x50410AcC7a4B7f3dcC36D102ed82b5437A34c477
