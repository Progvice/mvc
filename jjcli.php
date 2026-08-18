<?php

require __DIR__ . '/app/controllers/loader.php';

use Core\App\Enum\ConstraintEnum;

class JJCLI
{
    private $args;
    private $tables;
    public function __construct($argv)
    {
        $this->args = $argv;
        echo <<<EOT

   \033[94m JJCLI - Command line for JJMVC \033[0m 
   
EOT;
        if (count($this->args) < 2) {
            echo <<<EOT

            --- Write "php jjcli.php help" to get more information
            --- about commands and arguments.
EOT;
        } else if (count($this->args) > 1) {
            switch ($this->args[1]) {
                case 'help':
                    $this->Help();
                    break;
                case 'createpage':
                    $this->Createpage();
                    break;
                case 'devmode':
                    $this->Devmode();
                    break;
                case 'setup':
                    $this->setup($this->args[3] ?? false);
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
                case 'createtable':
                    $this->CreateTable($this->args);
                    break;
                case 'generatesql':
                    $this->GenerateSQL();
                    break;
                case 'init':
                    $this->initializeFromDocker();
                    break;
                default:
                    echo <<<EOT
                    \33[31m Command not found. \033[0m

EOT;
                    break;
            }
        }

        $this->tables = require __DIR__ . '/sql/order.php';
    }

    private function setup($force = false)
    {
        $mode = $this->args[2] ?? null;

        if (empty($mode)) {
            echo "\33[91m Invalid second argument.\033[0m\n";
            return;
        }

        // Use double ternary: 'dev' => 'development', 'prod' => 'production', else null
        $envKey = ($mode === 'dev') ? 'development' : (($mode === 'prod') ? 'production' : null);

        if ($envKey === null) {
            echo "\33[91m Invalid environment set for setup.\033[0m\n";
            return;
        }

        if (file_exists(__DIR__ . '/app/config.json') && $force === false) {
            echo "\33[91m This project has already been setup.\033[0m\n";
            return;
        }

        $configExample = json_decode(file_get_contents(__DIR__ . '/app/config-example.json'), true);
        if ($configExample === null) {
            echo "\33[91m Failed to parse config-example.json.\033[0m\n";
            return;
        }

        // Required env vars except password
        $dbHost = getenv('DB_HOST');
        $dbName = getenv('DB_NAME');
        $dbUser = getenv('DB_USER');

        // Password can fallback to empty string
        $dbPass = getenv('DB_PASS') ?: '';

        // Check required variables
        $missing = [];
        if (!$dbHost) $missing[] = 'DB_HOST';
        if (!$dbName) $missing[] = 'DB_NAME';
        if (!$dbUser) $missing[] = 'DB_USER';

        if (!empty($missing)) {
            echo "\33[91m Missing required environment variables: " . implode(', ', $missing) . "\033[0m\n";
            return;
        }

        // Fill database config
        $configExample[$envKey]['database']['host'] = $dbHost;
        $configExample[$envKey]['database']['dbname'] = $dbName;
        $configExample[$envKey]['database']['username'] = $dbUser;
        $configExample[$envKey]['database']['password'] = $dbPass;

        // Set environment string accordingly
        $configExample['environment'] = $envKey;

        // Save to config.json
        $json = json_encode($configExample, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        if ($json === false) {
            echo "\33[91m Failed to encode config to JSON.\033[0m\n";
            return;
        }

        $configPath = __DIR__ . '/app/config.json';
        if (file_put_contents($configPath, $json) === false) {
            echo "\33[91m Failed to write config.json file.\033[0m\n";
            return;
        }

        echo "\33[92m Configuration file created successfully at app/config.json.\033[0m\n";
    }



    private function Help()
    {
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
    private function Createpage()
    {
        $path = trim($this->args[2]);
        $name = '';
        if (!isset($this->args[3])) {
            $explodedPath = explode('/', $path);
            $pathLastPathPos = count($explodedPath) - 1;
            $name = $explodedPath[$pathLastPathPos];
        } else {
            $name = $this->args[3];
        }

        $this->CreateController($name, $path, 'page');
        $this->CreateRequest($name, $path, $path, true);
        $this->CreateView($path, true);
    }
    private function CreateView($path, $defaultView = true)
    {

        $defaultViewCode = match (true) {
            $defaultView === true => <<<PHP
            <?php echo \$page_content ?>
            PHP,
            default => ''
        };

        $relativePath = __DIR__ . '/app/views/pages' . $path;

        if (!is_dir($relativePath)) {
            mkdir($relativePath, 0755, true);
        }

        if (file_exists($relativePath . '/index.php')) {
            $this->sendConsoleMessage('View file already exists at ' . $relativePath);
            die();
        }
        file_put_contents($relativePath . '/index.php', $defaultViewCode);
    }
    private function CreateController($name, $path, $method = 'api')
    {
        $methodName = $name;
        $name = ucfirst($name);

        $controllerContent = match (true) {
            $method === 'api' => <<<PHP
Plugin::load('response');
\$response = new Response();
\$response->Send('json', [
    'status' => true,
    'message' => '{$name} is working!'
]);
PHP,
            $method === 'page' => <<<PHP
            Plugin::load('view');
                    \$view = new View();
                    \$view->variables = [
                        'page_content' => 'Hello World!'
                    ];
                    \$view->index(\$this->view);
            PHP,
            default => '',
        };

        $controllerNamespace = match (true) {
            $method === 'api' => 'use Core\\App\\Response;',
            $method === 'page' => 'use Core\\App\\View;',
            default => 'use Core\\App\\Response;',
        };

        $controller = <<<EOT
        <?php
        {$controllerNamespace}
        class {$name}Controller extends Controller {
            public function {$methodName}() {
                {$controllerContent}
            }
        }
        ?>
        EOT;

        if (!is_dir(__DIR__ . '/app/controllers' . $path)) {
            mkdir(__DIR__ . '/app/controllers' . $path, 0755, true);
        }

        if (file_exists(__DIR__ . '/app/controllers' . $path . '/index.php')) {
            $this->sendConsoleMessage('Controller already exists at ' . $path . ' - Exiting...', 'error', 'JJCLI - CreateController line ' . __LINE__);
            die();
        }

        file_put_contents(__DIR__ . '/app/controllers' . $path . '/index.php', $controller);
    }
    private function CreateRequest($name, $path, $uri, $noParams = false)
    {
        $methodName = $name;
        $name = ucfirst($name);

        $httpMethod = match (true) {
            str_contains($name, 'Create') => 'POST',
            str_contains($name, 'Read')   => 'GET',
            str_contains($name, 'Update') => 'PATCH',
            str_contains($name, 'Delete') => 'DELETE',
            default => 'GET'
        };
        $paramMethod =  match (true) {
            str_contains($name, 'Create') => 'create',
            str_contains($name, 'Read')   => 'read',
            str_contains($name, 'Update') => 'update',
            str_contains($name, 'Delete') => 'delete',
            default => $name
        };


        $params = PHP_EOL . <<<PHP
    "params" => [
        "{$paramMethod}" => [
            "method" => "{$methodName}"
        ] 
    ]    
PHP;
        $setParams = $paramMethod !== 'create' && $noParams === false ? $params : '';

        $requestfile = <<<PHP
<?php 
return [
    "httpMethod" => "{$httpMethod}",
    "url" => "{$uri}",
    "controller" => "{$uri}/index.php",
    "method" => "{$methodName}",
    "name" => "{$name}",
    "title" => "{$name}",{$setParams}
];
PHP;

        if (!is_dir(__DIR__ . '/app/requests' . $path)) {
            mkdir(__DIR__ . '/app/requests' . $path, 0755, true);
        }

        if (file_exists(__DIR__ . '/app/requests' . $path . '/index.php')) {
            $this->sendConsoleMessage('Request file already exists at ' . __DIR__ . '/app/requests' . $path . ' - Exiting...', 'error', 'JJCLI - CreateController line ' . __LINE__);
            die();
        }
        file_put_contents(__DIR__ . '/app/requests' . $path . '/index.php', $requestfile);
    }
    private function CreateCRUD()
    {
        if (empty($this->args[2])) {
            echo <<<EOT
\33[91m Invalid second argument.\033[0m


EOT;
            return;
        }
        $cfolder = __DIR__ . '/app/controllers/';
        $rfolder = __DIR__ . '/app/requests/';

        // CREATING REQUEST FILES
        if (!file_exists($rfolder . $this->args[2])) {
            mkdir($rfolder . $this->args[2], 0755, true);
        }
        if (!file_exists($rfolder . $this->args[2] . '/create')) {
            mkdir($rfolder . $this->args[2] . '/create');
            $this->CreateRequest($this->args[2] . 'Create', $this->args[2] . '/create', '/' . $this->args[2] . '/create');
        }
        if (!file_exists($rfolder . $this->args[2] . '/read')) {
            mkdir($rfolder . $this->args[2] . '/read');
            $this->CreateRequest($this->args[2] . 'Read', $this->args[2] . '/read', '/' . $this->args[2] . '/read');
        }
        if (!file_exists($rfolder . $this->args[2] . '/update')) {
            mkdir($rfolder . $this->args[2] . '/update');
            $this->CreateRequest($this->args[2] . 'Update', $this->args[2] . '/update', '/' . $this->args[2] . '/update');
        }
        if (!file_exists($rfolder . $this->args[2] . '/delete')) {
            mkdir($rfolder . $this->args[2] . '/delete');
            $this->CreateRequest($this->args[2] . 'Delete', $this->args[2] . '/delete', '/' . $this->args[2] . '/delete');
        }

        // CREATING CONTROLLERS 
        if (!file_exists($cfolder . $this->args[2])) {
            mkdir($cfolder . $this->args[2]);
        }
        if (!file_exists($cfolder . $this->args[2] . '/create')) {
            mkdir($cfolder . $this->args[2] . '/create');
            $this->CreateController($this->args[2] . 'Create', $cfolder . $this->args[2] . '/create');
        }
        if (!file_exists($cfolder . $this->args[2] . '/read')) {
            mkdir($cfolder . $this->args[2] . '/read');
            $this->CreateController($this->args[2] . 'Read', $cfolder . $this->args[2] . '/read');
        }
        if (!file_exists($cfolder . $this->args[2] . '/update')) {
            mkdir($cfolder . $this->args[2] . '/update');
            $this->CreateController($this->args[2] . 'Update', $cfolder . $this->args[2] . '/update');
        }
        if (!file_exists($cfolder . $this->args[2] . '/delete')) {
            mkdir($cfolder . $this->args[2] . '/delete');
            $this->CreateController($this->args[2] . 'Delete', $cfolder . $this->args[2] . '/delete');
        }
        clearstatcache();
    }

    private function Devmode()
    {
        $json = json_decode(file_get_contents(__DIR__ . '/app/config.json'));
        if (empty($this->args[2])) {
            echo <<<EOT
\33[91m Invalid second argument.\033[0m


EOT;
            return;
        }
        switch ($this->args[2]) {
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

    private function CreateModel()
    {
        if (empty($this->args[2])) {
            echo <<<EOT
\33[91m Invalid second argument.\033[0m


EOT;
            return;
        }

        if (file_exists(__DIR__ . '/app/models/' . $this->args[2] . '.php')) {
            echo <<<EOT
            \33[91m Model exists already! \033[0m
EOT;
            return;
        }
        $rules = '';

        $primaryKey = isset($this->args[4]) ? '\'primary_key\' => \'' . $this->args[4] . '\',' . "\n" : '';
        if (isset($this->args[4])) {
            $rules = $rules . '\'primary_key \' => ' . $this->args[4] . '\',' . "\n";
        }
        if (isset($this->args[3]) && $this->args[3] !== '--') {
            $rulesarray = explode(',', $this->args[3]);
            foreach ($rulesarray as $rule) {
                $ruleset = explode(':', $rule);
                $rcount = count($ruleset);
                $counter = 2;
                $rules = $rules . '            \'' . $ruleset[0] . '\' => [' . "\n";
                if (isset($ruleset[1]) && !empty($ruleset[1])) {
                    if ($counter !== $rcount) {
                        $rules = $rules . '                \'length\' => ' . $ruleset[1] . ',' . "\n";
                    } else {
                        $rules = $rules . '                \'length\' => ' . $ruleset[1];
                    }
                    $counter = $counter + 1;
                }
                if (isset($ruleset[2]) && !empty($ruleset[2])) {
                    if ($counter !== $rcount) {
                        $rules = $rules . '                \'type\' => \'' . $ruleset[2] . '\',' . "\n";
                    } else {
                        $rules = $rules . '                \'type\' => \'' . $ruleset[2] . '\'';
                    }
                    $counter = $counter + 1;
                }
                if (isset($ruleset[3]) && !empty($ruleset[3])) {
                    if ($counter !== $rcount) {
                        $rules = $rules . '                \'required\' => ' . $ruleset[3] . ',' . "\n";
                    } else {
                        $rules = $rules . '                \'required\' => ' . $ruleset[3];
                    }
                    $counter = $counter + 1;
                }
                if (isset($ruleset[4]) && !empty($ruleset[4])) {
                    if ($counter !== $rcount) {
                        $rules = $rules . '                \'unique\' => ' . $ruleset[4] . ',' . "\n";
                    } else {
                        $rules = $rules . '                \'unique\' => ' . $ruleset[4];
                    }
                    $counter = $counter + 1;
                }
                if ($rule === end($rulesarray)) {
                    $rules = $rules . "\n" . '            ]';
                } else {
                    $rules = $rules . "\n" . '            ],' . "\n";
                }
            }
        } else {
            $rules = <<<EOT
            {$primaryKey}
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
        \$this->rules = [{$rules}
        ];
    }
}

EOT;
        $modelfile = fopen(__DIR__ . '/app/models/' . $modelname . '.php', "w");
        fwrite($modelfile, $template);
        fclose($modelfile);
    }

    private function CreateJob()
    {
        if (empty($this->args[2])) {
            echo <<<EOT
\33[91m Invalid second argument.\033[0m


EOT;
            return;
        }

        if (file_exists(__DIR__ . '/app/jobs/' . $this->args[2] . '/index.php')) {
            echo <<<EOT
            \33[91m Job exists already! \033[0m
EOT;
            return;
        }

        $name = $this->args[2];



        $jobTemplate = <<<EOT
<?php 

class {$name}Job extends Jobs {
    
    public function init(\$args) {
        
    }

}

EOT;
    }

    private function CreateTemplate()
    {

        if (empty($this->args[2])) {
            echo <<<EOT
\33[91m Invalid second argument.\033[0m


EOT;
            return;
        }

        if (file_exists(__DIR__ . '/app/views/templates/' . $this->args[2] . '/index.php')) {
            echo <<<EOT
            \33[91m Template exists already! \033[0m
EOT;
            return;
        }
        $eot = 'EOT;';
        $template = <<<EOT
<?php

namespace Core\App\Template;

use Core\App\Template;

class {$this->args[2]} extends Template {
    public function load(\$values) {
        \$this->collectStyle(__DIR__);
        \$this->collectScript(__DIR__);
        return <<<EOT
            <h1>{$this->args[2]} works!</h1>
        {$eot}
    }
}

EOT;
        mkdir(__DIR__ . '/app/views/templates/' . $this->args[2]);
        $modelfile = fopen(__DIR__ . '/app/views/templates/' . $this->args[2] . '/index.php', "w");
        fwrite($modelfile, $template);
        fclose($modelfile);

        $cssFile = fopen(__DIR__ . '/app/views/templates/' . $this->args[2] . '/style.css', "w");
        fwrite($cssFile, "/* Template style */");
        fclose($cssFile);

        $jsFile = fopen(__DIR__ . '/app/views/templates/' . $this->args[2] . '/script.js', "w");
        fwrite($jsFile, "");
        fclose($jsFile);
    }

    private function CreateStringType($field, $len, $isUnique, $isRequired, $lastArrElem)
    {
        $stringEnd = $lastArrElem ? '' : ',';

        if ($len > 0 && $len <= 255) return $field . ' VARCHAR(' . $len . ')' . $isRequired . $isUnique . $stringEnd;
        if ($len > 255 && $len <= 65535) return $field . ' TEXT ' . $isRequired . $isUnique . $stringEnd;
        if ($len > 65535 && $len <= 16777215) return $field . ' MEDIUMTEXT ' . $isRequired . $isUnique . $stringEnd;
        if ($len > 16777215 && $len <= 4294967296) return $field . ' LONGTEXT ' . $isRequired . $isUnique . $stringEnd;
    }
    private function CreateNumberType($field, $len, $isUnique, $isRequired, $lastArrElem, $autoIncrement, $isUnsigned = true)
    {

        $type = $len <= 3 ? 'TINYINT' : ($len <= 5 ? 'SMALLINT' : ($len <= 8 ? 'MEDIUMINT' : ($len <= 10 ? 'INT' : 'BIGINT')));

        $sql = $field . ' ' . $type;
        if ($isUnsigned) $sql .= ' UNSIGNED';
        if ($isRequired) $sql .= ' NOT NULL';
        else $sql .= '';
        if ($autoIncrement) $sql .= ' AUTO_INCREMENT';
        if ($isUnique) $sql .= ' UNIQUE';
        $sql .= $lastArrElem ? '' : ',';

        return $sql;
    }

    private function CreateDate($field, $isUnique, $isRequired, $lastArrElem)
    {
        $conditions = ($isRequired === true ? ' NOT NULL' : '') . ($isUnique === true ? ' UNIQUE' : '') . ($lastArrElem ? '' : ',');
        return <<<EOS
        {$field} DATE{$conditions}
        EOS;
    }
    private function CreateDatetime($field, $isUnique, $isRequired, $lastArrElem)
    {
        $conditions = ($isRequired === true ? ' NOT NULL' : '') . ($isUnique === true ? ' UNIQUE' : '') . ($lastArrElem ? '' : ',');
        return <<<EOS
        {$field} DATETIME{$conditions}
        EOS;
    }

    private function CreateJSON($field, $isUnique, $isRequired, $lastArrElem)
    {
        $conditions = ($isRequired === true ? ' NOT NULL' : '') . ($isUnique === true ? ' UNIQUE' : '') . ($lastArrElem ? '' : ',');
        return <<<EOS
        {$field} JSON{$conditions}
        EOS;
    }

    private function toPascalCase(string $text)
    {
        $words = explode('_', $text);
        $words = array_map('ucfirst', $words);
        return implode('', $words);
    }

    function pascalToSnake($string)
    {
        $snake = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $string));
        return $snake;
    }

    private function CreateTable($args = null)
    {
        if ($args !== null) $this->args = $args;
        if (empty($this->args[2])) {
            echo <<<EOT
\33[91m Invalid second argument.\033[0m


EOT;
        }
        $snakeName = $this->pascalToSnake($this->args[2]);
        $name = $this->toPascalCase($this->args[2]);
        if (!file_exists(__DIR__ . '/app/models/' . $name . '.php')) {
            echo <<<EOT

\33[91m Invalid model name. Cannot create table called {$name}.\033[0m


EOT;
        }
        require_once __DIR__ . '/app/plugins/data/core/models/1.0/MainModel.php';
        require_once __DIR__ . '/app/models/' . ucfirst($name) . '.php';

        $className = 'Core\\App\\Models\\' . $name;

        $model = new $className;

        $rules = $model->LoadModelRules();

        $error = false;

        $fieldList = '';

        $pkAutoincrement = isset($rules['pk_autoincrement']) && $rules['pk_autoincrement'] === false ? ',' : ' AUTO_INCREMENT,';

        $primaryKey = isset($rules['primary_key']) ? ('    ' . $rules['primary_key'] . ' INT UNSIGNED NOT NULL' . $pkAutoincrement) : "";
        $primaryKeyForConstraints = isset($rules['primary_key']) ? $rules['primary_key'] : 'id';

        foreach ($rules as $field => $rulefields) {
            if (
                $field === 'primary_key'
                || $field === 'hasMany'
                || $field === 'belongsTo'
                || $field === 'created_at'
                || $field === 'modified_at'
                || $field === 'hasOne'
                || $field === 'hasMany'
                || $field === 'pk_autoincrement'
            ) continue;

            if (!isset($rulefields['type']) || empty($rulefields['type'])) {
                echo <<<EOT

\33[91m Warning! Field {$field} does not have type set.\033[0m


EOT;
                $error = true;
                break;
            }

            $type = $rulefields['type'];

            $lastArrElem = array_key_last($rules) === $field && $primaryKey === "" ? true : false;

            $isUnique = isset($rulefields['unique']) && $rulefields['unique'] === true ? ' UNIQUE' : '';
            $isRequired = isset($rulefields['required']) && $rulefields['required'] === true ? ' NOT NULL' : '';

            $autoIncrement = isset($rulefields['autoincrement']) ? true : false;

            $lineEnding = $lastArrElem === true ? "" : "\n";

            switch ($type) {
                case 'string':
                    $length = isset($rulefields['length']) ? $rulefields['length'] : 255;
                    $fieldList = $fieldList . '    ' . $this->CreateStringType($field, $length, $isUnique, $isRequired, $lastArrElem) . $lineEnding;
                    break;
                case 'number':
                    $length = isset($rulefields['length']) ? $rulefields['length'] : 10;
                    $fieldList = $fieldList . '    ' . $this->CreateNumberType($field, $length, $isUnique, $isRequired, $lastArrElem, $autoIncrement) . $lineEnding;
                    break;
                case 'date':
                    $fieldList = $fieldList . '    ' . $this->CreateDate($field, $isUnique, $isRequired, $lastArrElem) . $lineEnding;
                    break;
                case 'datetime':
                    $fieldList = $fieldList . '    ' . $this->createDatetime($field, $isUnique, $isRequired, $lastArrElem) . $lineEnding;
                    break;
                case 'json':
                    $fieldList = $fieldList . '    ' . $this->CreateJSON($field, $isUnique, $isRequired, $lastArrElem) . $lineEnding;
                    break;
            }
        }
        if ($error) return;

        $constraints = "";

        if (isset($rules['belongsTo']) && is_array($rules['belongsTo'])) {
            foreach ($rules['belongsTo'] as $key => $subClassName) {
                $params = null;
                if (!is_int($key)) {
                    $params = $subClassName;
                    $subClassName = $key;
                }

                if ($params !== null && !is_array($params)) {
                    $this->sendConsoleMessage('belongsTo value should be array. ' . gettype($params) . ' given at ' . $subClassName, 'warning');
                    continue;
                }

                if ($params !== null && count($params) > 1) {
                    if (gettype($params[0]) !== 'string') {
                        $this->sendConsoleMessage('belongsTo local field should be string. ' . gettype($params[0]) . ' given at ' . $subClassName, 'warning');
                        continue;
                    }
                    if (gettype($params[1]) !== 'string') {
                        $this->sendConsoleMessage('belongsTo relation field should be string. ' . gettype($params[1]) . ' given at ' . $subClassName, 'warning');
                        continue;
                    }
                }

                $constraint_settings = [];

                if ($params !== null && array_key_exists('ref', $params)) {
                    if (!is_array($params['ref'])) {
                        $this->sendConsoleMessage('belongsTo ref needs to be array. ' . gettype($params['ref']) . ' given at ' . $subClassName, 'warning');
                        return;
                    }
                    if (count($params['ref']) > 2 || count($params['ref']) < 1) {
                        $this->sendConsoleMessage('belongsTo ref should have minimum of 1 option and maximum of 2 options. ' . count($params['ref']) . ' given at ' . $subClassName, 'warning');
                        return;
                    }
                    if (!($params['ref'][0] instanceof ConstraintEnum)) {
                        $this->sendConsoleMessage('belongsTo ref value 0 is not instance of ' . ConstraintEnum::class);
                        return;
                    }
                    if (isset($params['ref'][1]) && !($params['ref'][1] instanceof ConstraintEnum)) {
                        $this->sendConsoleMessage('belongsTo ref value 0 is not instance of ' . ConstraintEnum::class);
                        return;
                    }

                    $duplicateConstraints = [false, null];

                    foreach (['DELETE', 'UPDATE'] as $sqlMethod) {
                        if (str_contains($params['ref'][0]->value, $sqlMethod) && str_contains($params['ref'][1]->value, $sqlMethod)) {
                            $duplicateConstraints = [true, $params['ref']];
                        }
                    }

                    if ($duplicateConstraints[0]) {
                        $invalid_ref_fields = implode(', ', [$params['ref'][0]->value, $params['ref'][1]->value]);
                        $this->sendConsoleMessage('belongsTo ref cannot have two same kind of actions. ' . $invalid_ref_fields . ' are set at ' . $subClassName, 'warning');
                        return;
                    }
                    $constraint_settings = [$params['ref'][0]->value, $params['ref'][1]->value];
                }

                $constraint_settings_text = '';

                if (count($constraint_settings) === 1) {
                    $constraint_settings_text = $constraint_settings[0];
                } else if (count($constraint_settings) === 2) {
                    $constraint_settings_text = implode("\n        ", $constraint_settings);
                }

                $explodedName = explode('\\', $subClassName);
                $subClassNameWoNamespace = lcfirst(end($explodedName));

                $subClassNameFieldName = $params !== null && count($params) > 1 ? $params[0] : $subClassNameWoNamespace . '_id';
                $primaryKeyForConstraints = $params !== null && count($params) > 1 ? $params[1] : $primaryKeyForConstraints;

                $array_key_end = $params !== null ? array_key_last($rules['belongsTo']) : end($rules['belongsTo']);

                $constraintEnding = $array_key_end === $subClassName ? '' : (count($rules['belongsTo']) > 1 ? ',' : '');
                $newConstraint = <<<EOS
                    CONSTRAINT fk_{$subClassNameWoNamespace}_{$snakeName}
                        FOREIGN KEY ({$subClassNameFieldName}) REFERENCES {$subClassNameWoNamespace}({$primaryKeyForConstraints})
                        {$constraint_settings_text}{$constraintEnding}
                EOS;

                $lastConstraint = end($rules['belongsTo']) === $subClassName ? "" : "\n\n";

                $constraints = $constraints . $newConstraint . $lastConstraint;

                $commaLastArr = $primaryKey === '' ? ',' : '';

                $fieldList = <<<EOS
                {$fieldList}{$commaLastArr}
                    {$subClassNameFieldName} INT UNSIGNED,
                EOS;
            }
        }

        $primaryKeyBottom = $primaryKey !== "" ? "    PRIMARY KEY (" . $rules['primary_key'] . ')' : '';

        $primaryKeyBottomEnding = $constraints !== '' ? ($primaryKeyBottom === '' ? '' : ',') : '';

        $primaryKeyBottom = $primaryKeyBottom . $primaryKeyBottomEnding;

        $sqlFile = <<<EOS
        CREATE TABLE IF NOT EXISTS {$snakeName} (
        {$primaryKey}
        {$fieldList}
        {$primaryKeyBottom}
        {$constraints}
        );
        EOS;


        if (isset($this->args[3]) && $this->args[3] === true) {
            return $sqlFile;
        }

        file_put_contents(__DIR__ . '/sql/' . $name . '.sql', $sqlFile);
    }

    private function updateSQL() 
    {
        $tables = $this->tables;
        $finalSqlFile = '';

        $sql_state = json_decode(file_get_contents(__DIR__ . '/sql/state.json'), true);

        foreach ($tables as $table) {
            $loaded_table = new $table();
            $table_rules = json_encode($loaded_table->LoadModelRules());
            $table_hash = hash('sha256', $table_rules);
            
             if (isset($sql_state[$name], $sql_state[$name]['hash']) && $table_hash !== $sql_state[$name]['hash']) {
                $this->sendConsoleMessage($name . ' table has changedddddd!', 'warning');
            }

        }
    }

    private function GenerateSQL()
    {

        if (!file_exists(__DIR__ . '/sql/order.php')) {
            echo <<<EOT

\33[91m Warning! sql/order.php does not exist. Create order of SQL tables to continue \033[0m


EOT;
            return;
        }

        if (!file_exists(__DIR__ . '/app/config.json')) {
            echo <<<EOT

\33[91m Warning! app/config.json does not exist! Database connection requires configuration \033[0m


EOT;
            return;
        }

        $tables = $this->tables;
        $finalSqlFile = '';

        $sql_state = json_decode(file_get_contents(__DIR__ . '/sql/state.json'), true);

        foreach ($tables as $table) {

            $loaded_table = new $table();

            $model_rules = json_encode($loaded_table->LoadModelRules());

            $explodedName = explode('\\', $table);
            $name = end($explodedName);
            $created_table = $this->CreateTable([null, null, $name, true]);
            $table_hash = hash('sha256', $model_rules);

            $this->updateSQL();

            if (isset($sql_state[$name], $sql_state[$name]['hash']) && $table_hash !== $sql_state[$name]['hash']) {
                $this->sendConsoleMessage($name . ' table has changed!', 'warning');
            }

            $sql_state[$name]['hash'] = hash('sha256', $model_rules);
            $finalSqlFile = $finalSqlFile . $created_table . "\n\n";
        }

        $config = json_decode(file_get_contents(__DIR__ . '/app/config.json'), true);
        $environment = $config['environment'] ?? null;

        if ($environment === null) {
            echo <<<EOT

\33[91m Warning! Invalid environment set to configuration file. Please set it to development or production. \033[0m


EOT;
            return;
        }

        $dbCredentials = $config[$environment]['database'];

        file_put_contents(__DIR__ . '/sql/init.sql', $finalSqlFile);
        try {
            $conn = new PDO(
                $dbCredentials['dbtype'] .
                    ':dbname=' . $dbCredentials['dbname'] .
                    ';host=' . $dbCredentials['host'] .
                    ';charset=' . $dbCredentials['charset'],
                $dbCredentials['username'],
                $dbCredentials['password']
            );
            $conn->beginTransaction();
            $conn->exec($finalSqlFile);
        } catch (PDOException $err) {
            $msg = $err->getMessage();
            echo <<<EOT

\33[91m Error! {$msg} \033[0m


EOT;
            if (isset($conn) && $conn->inTransaction()) {
                $conn->rollBack();
            }
            return;
        }

        file_put_contents(__DIR__ . '/sql/state.json', json_encode($sql_state, JSON_PRETTY_PRINT));

        echo "Generated SQL succesfully\n";
    }

    function initializeFromDocker()
    {
        // ensure we have args
        if (!isset($this->args[2])) {
            echo "Usage: php jjcli.php init <environment>\n";
            die(1);
        }

        $envName = $this->args[2]; // e.g. "development" or "production"

        $exampleFile = __DIR__ . '/app/config-example.json';
        $configFile  = __DIR__ . '/app/config.json';

        if (!file_exists($exampleFile)) {
            echo "Example config file not found: $exampleFile\n";
            die(1);
        }

        // Copy example to config.json (overwrite each time)
        if (!copy($exampleFile, $configFile)) {
            echo "Failed to copy $exampleFile to $configFile\n";
            die(1);
        }

        // Load config
        $config = json_decode(file_get_contents($configFile), true);
        if (!$config) {
            echo "Failed to parse $configFile\n";
            die(1);
        }

        // Load environment variables from system
        $dbHost     = getenv("DB_HOST") ?: "db";
        $dbName     = getenv("MYSQL_DATABASE") ?: "jjmvc";
        $dbUser     = getenv("MYSQL_USER") ?: "jjmvc";
        $dbPassword = getenv("MYSQL_PASSWORD") ?: "secret";

        // Update selected environment
        if (!isset($config[$envName])) {
            echo "Environment '$envName' not found in config.json\n";
            die(1);
        }

        $config["environment"] = $envName;
        $config[$envName]["database"]["host"]     = $dbHost;
        $config[$envName]["database"]["dbname"]   = $dbName;
        $config[$envName]["database"]["username"] = $dbUser;
        $config[$envName]["database"]["password"] = $dbPassword;

        // Save back to file
        $json = json_encode($config, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        if (file_put_contents($configFile, $json) === false) {
            echo "Failed to write $configFile\n";
            die(1);
        }

        echo "Configuration initialized and updated for environment '$envName'.\n";
    }
    private function sendConsoleMessage($msg, $type = 'warning', $source = '')
    {
        $colors = [
            'info' => "\033[97m",     // white
            'warning' => "\033[93m",  // yellow
            'error' => "\033[91m",    // red
        ];

        $preword = [
            'info' => 'Info:',
            'warning' => 'Warning!',
            'error' => 'Error!'
        ];

        $preword = isset($preword[$type]) ? $preword[$type] : '';

        $color = $colors[$type] ?? "\033[0m";

        $output =  <<<EOS
{$color}{$preword} {$msg} - {$source}\033[0m
EOS;
        echo $output . PHP_EOL;
    }
}
$cli = new JJCLI($argv);
