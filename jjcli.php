<?php
/*
 *  This is JJCLI and with it developer can make using JJMVC more easier.
 * 
 * 
 * 
 * 
 */
class JJCLI {
    private $args;
    public function __construct($argv) {
        $this->args = $argv;
        echo <<<EOT

   \033[94m JJCLI - Command line for JJMVC \033[0m 
   
EOT;
        if(count($this->args) < 2) {
            echo <<<EOT

            --- Write "php jjcli.php help" to get more information
            --- about commands and arguments.
EOT;
        }
        else if(count($this->args) > 1) {
            switch($this->args[1]) {
                case 'help':
                    $this->Help();
                break;
                case 'createpage': 
                    $this->Createpage();
                break;
                case 'devmode': 
                    $this->Devmode();
                break;
                case 'createcontroller': 
                    $this->CreateController();
                break;
                case 'createmodel':
                    $this->CreateModel();
                break;
                case 'createtemplate':
                    $this->CreateTemplate();
                break;
                case 'createcrud':
                    $this->CreateCRUD();
                break;
                default:
                    echo <<<EOT
                    \33[31m Command not found. \033[0m

EOT;
                break;
            }
        }
    }
    private function Help() {
        echo <<<EOT
         |________________________ COMMANDS _________________________|
            |                                                           |
            |   createpage [URI]                                        |
            |                                                           |
            |       This command creates new page. It automatically     |
            |       creates request, controller and view files for page.|
            |                                                           |
            |       Example                                             |
            |           jjcli.php createpage '/products'                |
            |           jjcli.php createpage '/aboutus/contactus'       |
            |                                                           |
            |   removepage [URI]                                        |
            |                                                           |
            |       This command removes pages. It removes pages        |
            |       request, controller and view files.                 |
            |                                                           |
            |       Example                                             |
            |                                                           |
            |           jjcli.php removepage '/products'                |
            |           jjcli.php removepage '/aboutus/contactus'       |
            |                                                           |
            |   addplugin PLUGIN_NAME                                   |
            |                                                           |
            |       Adds plugin to JJMVC. Requires that proper          |
            |       plugin server has been set to cliconf.json          |
            |                                                           |
            |       Example                                             |
            |                                                           |
            |           jjcli.php addplugin author:pluginname:version   |
            |                                                           |
            |   devmode production||development                         |
            |                                                           |
            |       Change environment to production or development.    |
            |                                                           |
            |       Example                                             |
            |                                                           |
            |           jjcli.php devmode production                    |
            |           jjcli.php devmode development                   |
            |                                                           |
            |   createmodel MODEL_NAME                                  |    
            |                                                           |
            |       Create new model                                    |
            |                                                           |
            |       Example                                             |
            |           jjcli.php createmodel users                     |
            |                                                           |
            |________________________ COMMANDS _________________________|
            
EOT;
    }
    private function Createpage() {

    }
    private function CreateController($name, $path) {
        $controller = <<<EOT
        <?php
            class {$name}Controller extends Controller {
                public function {$name}() {
                    plugin::load('response');
                    \$response = new Core\App\Response;
                    \$response->Send('json', [
                        'status' => true,
                        'message' => '{$name} is working!'
                    ]);
                }
            }
        ?>
EOT;
        file_put_contents($path . '/index.php', $controller);
    }
    private function CreateRequest($name, $path, $uri) {
        $requestfile = <<<EOT
        {
            "page": 
            {
                "url" : "{$uri}",
                "controller" : "{$uri}/index.php",
                "actions" : "{$name}",
                "name" : "{$name}",
                "title" : "{$name}"
            }
        }
EOT;
        file_put_contents($path . '/index.json', $requestfile);
    }
    private function CreateCRUD() {
        if(empty($this->args[2])) {
            echo <<<EOT
\33[91m Invalid second argument.\033[0m


EOT;
        return;
        }
        $cfolder = __DIR__ . '/app/controllers/';
        $rfolder = __DIR__ . '/app/requests/';

        // CREATING REQUEST FILES
        if(!file_exists($rfolder . $this->args[2])) {
            mkdir($rfolder . $this->args[2]);
        }
        if(!file_exists($rfolder . $this->args[2] . '/create')) {
            mkdir($rfolder . $this->args[2] . '/create');
            $this->CreateRequest($this->args[2], $rfolder . $this->args[2] . '/create', '/' . $this->args[2] . '/create');
        }
        if(!file_exists($rfolder . $this->args[2] . '/read')) {
            mkdir($rfolder . $this->args[2] . '/read');
            $this->CreateRequest($this->args[2], $rfolder . $this->args[2] . '/read', '/' . $this->args[2] . '/read');
        }
        if(!file_exists($rfolder . $this->args[2] . '/update')) {
            mkdir($rfolder . $this->args[2] . '/update');
            $this->CreateRequest($this->args[2], $rfolder . $this->args[2] . '/update', '/' . $this->args[2] . '/update');
        }
        if(!file_exists($rfolder . $this->args[2] . '/delete')) {
            mkdir($rfolder . $this->args[2] . '/delete');
            $this->CreateRequest($this->args[2], $rfolder . $this->args[2] . '/delete', '/' . $this->args[2] . '/delete');
        }

        // CREATING CONTROLLERS 
        if(!file_exists($cfolder . $this->args[2])) {
            mkdir($cfolder . $this->args[2]);
        }
        if(!file_exists($cfolder . $this->args[2] . '/create')) {
            mkdir($cfolder . $this->args[2] . '/create');
            $this->CreateController($this->args[2], $cfolder . $this->args[2] . '/create');
        }
        if(!file_exists($cfolder . $this->args[2] . '/read')) {
            mkdir($cfolder . $this->args[2] . '/read');
            $this->CreateController($this->args[2], $cfolder . $this->args[2] . '/read');
        }
        if(!file_exists($cfolder . $this->args[2] . '/update')) {
            mkdir($cfolder . $this->args[2] . '/update');
            $this->CreateController($this->args[2], $cfolder . $this->args[2] . '/update');
        }
        if(!file_exists($cfolder . $this->args[2] . '/delete')) {
            mkdir($cfolder . $this->args[2] . '/delete');
            $this->CreateController($this->args[2], $cfolder . $this->args[2] . '/Delete');
        }
        clearstatcache();
    }

    private function Devmode() {
        $json = json_decode(file_get_contents(__DIR__ . '/app/config.json'));
        if(empty($this->args[2])) {
            echo <<<EOT
\33[91m Invalid second argument.\033[0m


EOT;
        return;
        }
        switch($this->args[2]) {
            case 'development':
                $json->environment = 'development';
            break;
            case 'production': 
                $json->environment = 'production';
            break;
            default:
                echo <<<EOT
                   \33[31m Invalid environment.\033[0m 
                EOT;
                return;
            break;
        }
        echo <<<EOT
           \33[91m Environment changed succesfully to {$json->environment}\033[0m 
        EOT;
        file_put_contents(__DIR__ . '/app/config.json', json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }

    private function CreateModel() {
        if(empty($this->args[2])) {
            echo <<<EOT
\33[91m Invalid second argument.\033[0m


EOT;
            return;
        }

        if(file_exists(__DIR__ . '/app/models/' . $this->args[2] . '.php')){
            echo <<<EOT
            \33[91m Model exists already! \033[0m
EOT;
            return;
        }
        $rules = '';
        if(isset($this->args[4])) {
            $rules = $rules . '\'primary_key \' => ' . $this->args[4] . '\',' . "\n";
        }
        if(isset($this->args[3])) {
            $rulesarray = explode(',', $this->args[3]);
            foreach($rulesarray as $rule) {
                $ruleset = explode(':', $rule);
                $rcount = count($ruleset);
                $counter = 2;
                $rules = $rules . '            \'' . $ruleset[0] . '\' => [' . "\n";
                if(isset($ruleset[1]) && !empty($ruleset[1])) {
                    if($counter !== $rcount) {
                        $rules = $rules . '                \'length\' => ' . $ruleset[1] . ',' . "\n";
                    }
                    else {
                        $rules = $rules . '                \'length\' => ' . $ruleset[1];
                    }
                    $counter = $counter + 1;
                }
                if(isset($ruleset[2]) && !empty($ruleset[2])) {
                    if($counter !== $rcount) {
                        $rules = $rules . '                \'type\' => \'' . $ruleset[2] . '\',' . "\n";
                    }
                    else {
                        $rules = $rules . '                \'type\' => \'' . $ruleset[2] . '\'';
                    }
                    $counter = $counter + 1;
                }
                if(isset($ruleset[3]) && !empty($ruleset[3])) {
                    if($counter !== $rcount) {
                        $rules = $rules . '                \'required\' => ' . $ruleset[3] . ',' . "\n";
                    }
                    else {
                        $rules = $rules . '                \'required\' => ' . $ruleset[3];
                    }
                    $counter = $counter + 1;
                }
                if(isset($ruleset[4]) && !empty($ruleset[4])) {
                    if($counter !== $rcount) {
                        $rules = $rules . '                \'unique\' => ' . $ruleset[4] . ',' . "\n";
                    }
                    else {
                        $rules = $rules . '                \'unique\' => ' . $ruleset[4];
                    }
                    $counter = $counter + 1;
                }
                if($rule === end($rulesarray)) {
                    $rules = $rules . "\n" . '            ]';
                }
                else {
                    $rules = $rules . "\n" . '            ],' . "\n";
                }
            }
        }
        else {
        $rules = <<<EOT
        'example_column' => [
                        'length' => 255,
                        'type' => 'string',
                        'required' => true,
                        'unique' => false
                    ]
EOT;
        }
        $modelname = ucwords($this->args[2]);
        $template = <<<EOT
        <?php
        namespace Core\App\Models;
        class {$modelname} extends MainModel {
            protected \$rules;
            public function __construct() {
                \$this->rules = [
        {$rules}
                ];
            }
        }
        ?>
EOT;
        $modelfile = fopen(__DIR__ . '/app/models/' . $modelname . '.php', "w");
        fwrite($modelfile, $template);
        fclose($modelfile);
    }
    private function CreateTemplate() {

        if(empty($this->args[2])) {
            echo <<<EOT
\33[91m Invalid second argument.\033[0m


EOT;
            return;
        }

        if(file_exists(__DIR__ . '/app/views/templates/' . $this->args[2] . '.php')){
            echo <<<EOT
            \33[91m Model exists already! \033[0m
EOT;
            return;
        }
        $eot = 'EOT;';
        $template = <<<EOT
        <?php
        namespace Core\App\Template;
        class {$this->args[2]} extends \Core\App\Template {
            static protected \$template;
            public function Load(\$values) {
                self::\$template = <<<EOT
                    <h1>{$this->args[2]} works!</h1>
                {$eot}
                return self::\$template;
            }
        }
        ?>
EOT;
        $modelfile = fopen(__DIR__ . '/app/views/templates/' . $this->args[2] . '.php', "w");
        fwrite($modelfile, $template);
        fclose($modelfile);
    }
}
$cli = new JJCLI($argv);