# JJMVC 

## Introduction

JJMVC is PHP framework that makes it easier to build full stack web applications. JJMVC provides CLI tools and easy way of handling of database queries, view templating and using 3rd party tools. 

## 1. Essentials

Let's start with essentials of JJMVC. 

### 1.1 Folder structure
```
root/
├── app/
|   ├── controllers/ - Route controllers
|   |   ├── loader.php - JJMVC plugin loader
|   |   └── Controller.php - Main controller logic for MVC
|   |
|   ├── lang/ - Contains language files. 
|   ├── models/ - Business logic / schematics of your application 
|   ├── plugins/ - JJMVC extensions (aka. plugins) that can be loaded everywhere on JJMVC
|   |   ├── composer/ - contains composer files
|   |   ├── plugin_aliases.json - Contains all the aliases that plugins have
|   |   ├── plugins.json - Contains all the information what plugins are available 
|   |   ├── data/ - Contains plugin logic 
|   |   └── json/ - Plugin data storage
|   |
|   ├── requests/ - Contain details of every request in PHP files
|   ├── tests (WIP) - Is not set yet. Work in progress
|   └── views/
|       ├── custom/ - Custom headers and footers for view
|       ├── pages/ - Contains page view files
│       └── templates/ - Contains view templates that can be called within pages
|
├── public/ - Everything in this folder are publicly available for web server
|   ├── css/ - Project CSS files
|   ├── js/ - Project JS files
|   ├── img/ - Store for image files
|   └── index.php - Entrypoint for the whole application. Every request goes through this file except files in public folder
|
└── jjcli.php - JJMVC CLI tool that makes developers life so much easier when using JJMVC
```

Now we have gone through the folder structure. Folder structure gives you information of how thins 

### 1.2 How does data flow?

Entrypoint for JJMVC is public/index.php. From there on everything goes through app/controllers/controller.php. Controller.php tries to route request to appropriate route based on request URI. If controller doesn't find any proper route then request is redirected to 404 page. Controller also makes sure, that request method is appropriate. Request route does not contain request method automatically and it needs to be set manually to request file.

TL;DR

    public/index.php -> app/controllers/Controller.php -> app/controllers/[URI]/index.php

Controller method has been set on request files.

### 1.3 Controllers

Controllers work based on request URI and request file configuration. Fallback of Controller is to send user /404. 
Controller starts with name like "class exampleController extends Controller" and main method usually is the same name called "example". However method name is really defined in request file and it can be anything. Suggestion is to use same name on classname and on function ex. 
"exampleController" -> "public function example()".

### 1.4 Requests

Requests contain all the parameters of request that needs to be met. 

**method** - JJMVC method function of controller  
**httpMethod** - which HTTP method is used (POST, GET, PUT, PATCH, DELETE)  
**title** - This can be used on view to show certain title for page  
**controller** - Defines path of controller location inside controllers/ folder  
**hidden** - This parameter was initially meant for hiding pages from listing of all pages. This does not have any real use case nowadays.  
**id** - Does not really have any use case. Will be removed in the future  
**params** - Contains all the possible params of this request route. Params will be explained later  

### 1.5 Plugins

Plugins are essential part of JJMVC. Plugin are pieces of code that can be loaded everywhere within JJMVC. Plugins provide 
possibility of creating flexible solutions. Plugins can also include other plugins. I have been planning on creating 
dependency tree of plugins like in other package managers (Composer, npm etc). However for now this is just theoretical solution and I will look up to it in the future if JJMVC ever even gets more users or if I feel like programming dependency system. 

Plugins can be loaded easily by just writing  

    Plugin::load('response')

Or another way of writing with full path is  

    Plugin::load('core:response:1.0')

Plugin aliases can be set at app/plugins/plugin_aliases.json.  

### 1.6 Templates and Template loader

Templates are part of view that can be loaded within view. Templates can receive data and use that data to view information. Creating templates is easy as you can just use CLI tool to create new template. 

**Create new template**

    php jjcli.php createtemplate ExampleTemplate

This will create new template called "ExampleTemplate". Now you can easily load it using JJMVC built-in templateloader. Header has instance of Templateloader so you don't really need to define it yourself. 

**How to include template file in view?**

    $template->load(['name' => 'ExampleTemplate', 'data' => [ANY_DATA_HERE]])

After that ExampleTemplate gets loaded and is shown in view. 

#### 1.6.1 API templates

Some templates are available to be loaded through using /template/{templateName} request. You can see and modify templates that are available at **app/config/apitemplates.php**. This could be useful if frontend needs to fetch new view for interactive changes and without changing the page. It can give user better user experience in the end. However be cautios when making it possible to load templates that receive user data. This makes JJMVC more vulnerable to XSS attacks. 

### 1.7 Models

Models contains the business logic of your application. You can define new entities and connections between those entities easily. 

JJCLI provides tools for creating new models, generating SQL out of those models and inserting SQL files in correct order. 

**Create new model**

    php jjcli.php createmodel example -- id

**This command defines that**  
1. We want to create new model called "example"
2. -- means that we want to bypass field inputs for now
3. "id" means that we want to use field "id" as primary key

Field data types that are available and working at the moment are

- number
- string
- date

These data types will be extented with more data types in the future. 

#### 1.7.1 order.php file

You can find order.php file at root/sql folder. order.php contains details of which order should every entity be generated to SQL. Child entities have dependencies of parent entities so table of parent entities need to be created first before child entities.

