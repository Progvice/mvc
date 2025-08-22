<?php
/*
 *  This is JJCLI and with it developer can make using JJMVC more easier.
 * 
 * 
 * 
 * 
 */

define('APP_PATH', __DIR__ . '/../app');
define('CONTROLLER_PATH', APP_PATH . '/controllers/');
define('MODEL_PATH', APP_PATH . '/models');
define('REQUEST_PATH', APP_PATH . '/requests');
define('VIEW_PATH', APP_PATH . '/views/pages');
define('PLUGIN_PATH', APP_PATH . '/plugins');
define('JSON_PATH', APP_PATH . '/plugins/json');
define('DATA_PATH', APP_PATH . '/views/data');
define('TEMPLATE_PATH', APP_PATH . '/views/templates/');

require __DIR__ . '/app/controllers/loader.php';

class JJCLI
{
    private $args;
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
                    $this->CreateTable();
                    break;
                case 'generatesql':
                    $this->GenerateSQL();
                    break;
                default:
                    echo <<<EOT
                    \33[31m Command not found. \033[0m

EOT;
                    break;
            }
        }
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
    private function Createpage() {}
    private function CreateController($name, $path)
    {

        $name = $this->args[2];
        $controller = <<<EOT
<?php
    use Core\App\Response;
    class {$name}Controller extends Controller {
        public function {$name}() {
            Plugin::load('response');
            \$response = new Response;
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
    private function CreateRequest($name, $path, $uri)
    {
        $requestfile = <<<EOT

<?php 

return [
    "url" => "{$uri}",
    "controller" => "{$uri}/index.php",
    "actions" => "{$name}",
    "name" => "{$name}",
    "title" => "{$name}"
];

EOT;
        file_put_contents($path . '/index.php', $requestfile);
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
            mkdir($rfolder . $this->args[2]);
        }
        if (!file_exists($rfolder . $this->args[2] . '/create')) {
            mkdir($rfolder . $this->args[2] . '/create');
            $this->CreateRequest($this->args[2], $rfolder . $this->args[2] . '/create', '/' . $this->args[2] . '/create');
        }
        if (!file_exists($rfolder . $this->args[2] . '/read')) {
            mkdir($rfolder . $this->args[2] . '/read');
            $this->CreateRequest($this->args[2], $rfolder . $this->args[2] . '/read', '/' . $this->args[2] . '/read');
        }
        if (!file_exists($rfolder . $this->args[2] . '/update')) {
            mkdir($rfolder . $this->args[2] . '/update');
            $this->CreateRequest($this->args[2], $rfolder . $this->args[2] . '/update', '/' . $this->args[2] . '/update');
        }
        if (!file_exists($rfolder . $this->args[2] . '/delete')) {
            mkdir($rfolder . $this->args[2] . '/delete');
            $this->CreateRequest($this->args[2], $rfolder . $this->args[2] . '/delete', '/' . $this->args[2] . '/delete');
        }

        // CREATING CONTROLLERS 
        if (!file_exists($cfolder . $this->args[2])) {
            mkdir($cfolder . $this->args[2]);
        }
        if (!file_exists($cfolder . $this->args[2] . '/create')) {
            mkdir($cfolder . $this->args[2] . '/create');
            $this->CreateController($this->args[2], $cfolder . $this->args[2] . '/create');
        }
        if (!file_exists($cfolder . $this->args[2] . '/read')) {
            mkdir($cfolder . $this->args[2] . '/read');
            $this->CreateController($this->args[2], $cfolder . $this->args[2] . '/read');
        }
        if (!file_exists($cfolder . $this->args[2] . '/update')) {
            mkdir($cfolder . $this->args[2] . '/update');
            $this->CreateController($this->args[2], $cfolder . $this->args[2] . '/update');
        }
        if (!file_exists($cfolder . $this->args[2] . '/delete')) {
            mkdir($cfolder . $this->args[2] . '/delete');
            $this->CreateController($this->args[2], $cfolder . $this->args[2] . '/delete');
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
        \$this->rules = [
{$rules}
        ];
    }
}

EOT;
        $modelfile = fopen(__DIR__ . '/app/models/' . $modelname . '.php', "w");
        fwrite($modelfile, $template);
        fclose($modelfile);
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
        require __DIR__ . '/app/models/' . ucfirst($name) . '.php';

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

            switch ($type) {
                case 'string':
                    $length = isset($rulefields['length']) ? $rulefields['length'] : 255;
                    $fieldList = $fieldList . '    ' . $this->CreateStringType($field, $length, $isUnique, $isRequired, $lastArrElem) . "\n";
                    break;
                case 'number':
                    $length = isset($rulefields['length']) ? $rulefields['length'] : 10;
                    $fieldList = $fieldList . '    ' . $this->CreateNumberType($field, $length, $isUnique, $isRequired, $lastArrElem, $autoIncrement) . "\n";
                    break;
                case 'date':
                    $fieldList = $fieldList . '    ' . $this->CreateDate($field, $isUnique, $isRequired, $lastArrElem) . "\n";
                    break;
            }
        }
        if ($error) return;

        $constraints = "";

        if (isset($rules['belongsTo']) && is_array($rules['belongsTo'])) {
            foreach ($rules['belongsTo'] as $subClassName) {

                $explodedName = explode('\\', $subClassName);
                $subClassNameWoNamespace = lcfirst(end($explodedName));

                $fieldList = <<<EOS
                {$fieldList}
                    {$subClassNameWoNamespace}_id INT UNSIGNED,
                EOS;

                $constraintEnding = end($rules['belongsTo']) === $subClassName ? '' : (count($rules['belongsTo']) > 1 ? ',' : '');
                $newConstraint = <<<EOS
                    CONSTRAINT fk_{$subClassNameWoNamespace}_{$snakeName}
                        FOREIGN KEY ({$subClassNameWoNamespace}_id) REFERENCES {$subClassNameWoNamespace}({$primaryKeyForConstraints})
                        ON DELETE CASCADE{$constraintEnding}
                EOS;

                $lastConstraint = end($rules['belongsTo']) === $subClassName ? "" : "\n\n";

                $constraints = $constraints . $newConstraint . $lastConstraint;
            }
        }

        $primaryKeyBottom = $primaryKey !== "" ? "    PRIMARY KEY (" . $rules['primary_key'] . ')' : '';

        $primaryKeyBottomEnding = $constraints !== '' ? ',' : '';

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

        $tables = include __DIR__ . '/sql/order.php';
        $finalSqlFile = '';
        foreach ($tables['order'] as $table) {
            $explodedName = explode('\\', $table);
            $name = end($explodedName);
            $finalSqlFile = $finalSqlFile . $this->CreateTable([null, null, $name, true]) . "\n\n";
        }


        $config = json_decode(file_get_contents(__DIR__ . '/app/config.json'), true);
        $environment = $config['environment'] ?? null;

        if ($environment === null) {
            echo <<<EOT

\33[91m Warning! Invalid environment set to configuration file. Please set it to development or production. \033[0m


EOT;
            return;
        }

        $finalSqlFile = $finalSqlFile . <<<EOS
        INSERT INTO personel (firstname, lastname, email, phonenumber, birthday)
        VALUES 
        ('Jani', 'Juuso', 'jani.juuso@example.com', '0401234567', '1999-03-06'),
        ('Laura', 'Korhonen', 'laura.korhonen@example.com', '0509876543', '1988-11-30'),
        ('Mikko', 'Virtanen', 'mikko.virtanen@example.com', '0412345678', '1992-02-25'),
        ('Sari', 'Niemi', 'sari.niemi@example.com', '0501122334', '1995-07-14'),
        ('Timo', 'Heikkinen', 'timo.heikkinen@example.com', '0411223344', '1985-09-09'),
        ('Anna', 'Laine', 'anna.laine@example.com', '0505566778', '1990-04-22'),
        ('Jukka', 'Mäkinen', 'jukka.makinen@example.com', '0409988776', '1987-12-15'),
        ('Katja', 'Salmi', 'katja.salmi@example.com', '0506677889', '1993-06-10'),
        ('Petri', 'Koskinen', 'petri.koskinen@example.com', '0413344556', '1982-08-03'),
        ('Marja', 'Hämäläinen', 'marja.hamalainen@example.com', '0502233445', '1996-01-28');
        EOS;

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

            $conn->exec($finalSqlFile);
        } catch (Error $err) {
            echo <<<EOT

\33[91m Warning! Could not connect to database \033[0m


EOT;
            return;
        }
    }
}
$cli = new JJCLI($argv);
