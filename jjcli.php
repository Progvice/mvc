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
                case 'createmodel':
                    $this->CreateModel();
                break;
                case 'createtemplate':
                    $this->CreateTemplate();
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

        $modelname = ucwords($this->args[2]);
        $template = <<<EOT
        <?php
        namespace Core\App\Models;
        class {$modelname} {
            protected \$rules;
            public function __construct() {
                \$this->rules = [
                    'primary_key' => 'id',
                    'example_column' => [
                        'length' => 255,
                        'type' => 'string',
                        'required' => true,
                        'unique' => false
                    ]
                ];
            }
        }
        ?>
        EOT;
        $modelfile = fopen(__DIR__ . '/app/models/' . $this->args[2] . '.php', "w");
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