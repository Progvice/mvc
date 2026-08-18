<?php

namespace Core\App\Models;

require_once __DIR__ . '/ConstraintEnum.php';

use Core\App\DB;
use \Plugin;

class MainModel
{
    protected $model;
    protected $mname;
    protected $conn;
    protected $rules;
    private $model_short_name;
    private $submodels = [];

    private function setLoadedModel(MainModel $model, string $name)
    {
        $model->conn = $this->conn;
        $model->model = $model;
        $model->model_short_name = $name;
        $model->mname = $model->rules['table'] ?? $name;
    }

    public function CallModel($name, $loadSubModel = false)
    {
        Plugin::load('db');
        if (empty($this->conn)) {
            $this->conn = DB::Connect();
        }
        $name = ucfirst($name);
        if (!file_exists(MODEL_PATH . '/' . $name . '.php')) {
            return [
                'status' => false,
                'msg' => 'Model not found'
            ];
        }
        require_once MODEL_PATH . '/' . $name . '.php';
        $modelname = 'Core\App\Models\\' . $name;
        $model = new $modelname;
        $this->setLoadedModel($model, $name);

        if ($loadSubModel) {
            $this->submodels[$name] = [];
            $this->submodels[$name]['model'] = $model;
            $this->submodels[$name]['rules'] = $model->LoadModelRules();
            $this->submodels[$name]['name'] = $name;
        }
        else {
            $this->model = $model;
            $this->rules = $this->model->rules;
            $this->model_short_name = $name;
            $this->mname = $model->mname;
        }
    }

    // DO NOT USE THIS FOR USER INPUT
    public function RawSQL($sql)
    {
        $this->conn = DB::Connect();
        $this->conn->exec($sql);
    }

    private function pascalToSnake($string)
    {
        $snake = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $string));
        return $snake;
    }

    public function GetRequiredFields($class_name = null)
    {
        $requiredFields = [];

        if (empty($this->rules)) {
            return [
                'status' => false,
                'msg' => 'This action requires use of model'
            ];
        }

        foreach ($this->rules as $field => $ruleset) {
            if (array_key_exists('required', $ruleset) && $ruleset['required'] === true) {
                $requiredFields[] = $field;
            }
        }
        return $requiredFields;
    }

    public function GetFields()
    {
        if (empty($this->rules)) {
            return [
                'status' => false,
                'msg' => 'This action requires use of model'
            ];
        }
        $fields = [];

        foreach ($this->rules as $field => $ruleset) {
            if (!array_key_exists('type', $ruleset)) continue;
            $fields[] = $field;
        }
        return $fields;
    }

    public function LoadModelRules()
    {
        return $this->rules;
    }

    /*
     *  @return true||false - False means that datatype is not correct
     * 
     */
    private function CheckDataType($data)
    {
        $rvalue = [
            'status' => true
        ];
        foreach ($this->model->rules as $name => $rules) {
            if (!array_key_exists('type', $rules)) continue;
            if (!array_key_exists($name, $rules)) continue;

            switch ($rules['type']) {
                case 'string':

                    if (!is_string($data[$name])) {
                        $rvalue = [
                            'status' => false,
                            'column' => $name,
                            'msg' => 'notstring'
                        ];
                    }

                    break;
                case 'array':

                    if (!is_array($data[$name])) {
                        $rvalue = [
                            'status' => false,
                            'column' => $name,
                            'msg' => 'notarray'
                        ];
                    }

                    break;
                case 'int':

                    if (!is_int($data[$name])) {
                        $rvalue = [
                            'status' => false,
                            'column' => $name,
                            'msg' => 'notinteger'
                        ];
                    }

                    break;
                case 'decimal':

                    if (!is_float($data[$name])) {
                        $rvalue = [
                            'status' => false,
                            'column' => $name,
                            'msg' => 'notdecimal'
                        ];
                    }

                    break;
                case 'tinyint':

                    if ($data[$name] > 255) {
                        $rvalue = [
                            'status' => false,
                            'column' => $name,
                            'msg' => 'tinyintoverflow'
                        ];
                    }

                    break;
                case 'date':

                    break;
                default:
                    $rvalue = [
                        'status' => false,
                        'msg' => 'invalidtype'
                    ];
                    break;
            }
        }
        return $rvalue;
    }

    /*
     *  @return true||false - False means that data length is not correct
     * 
     */
    private function CheckLength($data)
    {
        $rvalue = [
            'status' => true
        ];
        foreach ($this->model->rules as $name => $rules) {
            if (!array_key_exists('length', $rules)) continue;
            if (!isset($rules[$name])) continue;

            if (strlen($data[$name]) > $rules['length']) {
                $rvalue = [
                    'status' => false,
                    'column' => $name,
                    'msg' => 'toolong'
                ];
                continue;
            }
        }
        return $rvalue;
    }

    /*
     *  IsRequired() 
     *  @return true||false - False means that data is not set.
     * 
     */
    private function IsRequired($data)
    {
        $rvalue = [
            'status' => true
        ];
        foreach ($this->model->rules as $name => $rules) {
            if (!array_key_exists('required', $rules)) continue;
            if (!isset($rules['required'])) continue;

            if (empty($data[$name])) {
                $rvalue = [
                    'status' => false,
                    'column' => $name,
                    'msg' => 'is empty'
                ];
            }
        }
        return $rvalue;
    }

    private function IsRequiredUpdate($data)
    {
        if (!isset($this->model->rules[$data['column']])) {
            return [
                'status' => false,
                'column' => $data['column'],
                'msg' => 'does not exist'
            ];
        }
        if (!isset($this->model->rules[$data['column']]['required'])) {
            return [
                'status' => true,
            ];
        }
        if ($this->model->rules[$data['column']]['required'] === true) {
            if (empty($data['value'])) {
                return [
                    'status' => false,
                    'column' => $data['column'],
                    'msg' => $data['column'] . ' is empty'
                ];
            }
        }
        return [
            'status' => true
        ];
    }

    private function IsUnique($data)
    {
        $rvalue = [
            'status' => true
        ];
        foreach ($this->model->rules as $name => $rules) {
            if (!is_array($rules)) continue;
            if (!array_key_exists($name, $data)) continue;
            if (!array_key_exists('unique', $rules)) continue;
            if ($rules['unique'] !== true) continue;

            $boolval = $this->Select([
                'where' => [
                    'normal' => [
                        $name => $data[$name]
                    ]
                ]
            ]);
            if (!empty($boolval)) {
                $msg_string = $name . 'exists';
                $msg = LANG[$msg_string] ?? $msg_string;
                $rvalue = [
                    'status' => false,
                    'msg' => $msg
                ];
            }
        }
        return $rvalue;
    }

    /*
     *  Insert()
     *  
     *  @desc   Insert data to database.
     * 
     *  @example    
     * 
     *  $data = [
     *          'title' => 'Title example',
     *          'description' => 'This is just example description.'
     *  ];
     * 
     *  Insert($data);
     * 
     *  @return  boolean
     *      
     */
    public function Insert($data, $params = [])
    {
        $cdt = $this->CheckDataType($data);
        $cl = $this->CheckLength($data);
        $ir = $this->IsRequired($data);
        $iu = $this->IsUnique($data);

        if (!$cdt['status']) return $cdt;
        if (!$cl['status']) return $cl;
        if (!$ir['status']) return $ir;
        if (!$iu['status']) return $iu;

        if (isset($data['password']) && empty($params['PASSWORD_NO_HASH'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_ARGON2I, [
                'memory_cost' => CONFIG['argon_settings']['memory_cost'] ?? 8**6,
                'time_cost' => CONFIG['argon_settings']['time_cost'] ?? 4,
                'threads' => CONFIG['argon_settings']['threads'] ?? 2
            ]);
        }

        $query = 'INSERT INTO ' . $this->pascalToSnake($this->mname) . ' ';
        $columns = '(';
        $values = '(';
        $last_arr_elem = end($data);
        $execarr = [];
        foreach ($data as $column_name => $column_value) {
            $cnarr = ':' . $column_name;
            $execarr[$cnarr] = $column_value;
            if ($last_arr_elem === $column_value) {
                $columns .= $column_name . ') VALUES ';
                $values .= ':' . $column_name . ');';
                break;
            }
            $columns .= $column_name . ', ';
            $values .= ':' . $column_name . ', ';
        }
        $query .= $columns . $values;
        $result = $this->conn->prepare($query);
        $returnval = $result->execute($execarr);
        if ($returnval === true) {
            return [
                'status' => true,
                'msg' => 'insertsuccesful',
                'id' => $this->conn->lastInsertId(),
            ];
        }
        return [
            'status' => false,
            'msg' => 'unknownerror'
        ];
    }

    public function Update($data, $params = [])
    {
        $cdt = $this->CheckDataType($data['data']);
        $cl = $this->CheckLength($data['data']);
        $iu = $this->IsUnique($data['data']);

        if (!$cdt['status']) {
            return [
                'status' => false,
                'msg' => 'datatypeinvalid'
            ];
        } else if (!$cl['status']) {
            return [
                'status' => false,
                'msg' => 'lengthinvalid'
            ];
        } else if (!$iu['status']) {
            return [
                'status' => false,
                'msg' => $iu['msg']
            ];
        }

        $query = 'UPDATE ' . $this->pascalToSnake($this->mname) . ' SET ';
        $execarr = [];
        if (!isset($data['data'])) {
            return [
                'status' => false,
                'msg' => 'datafieldempty'
            ];
        }
        if (!isset($data['where'])) {
            return [
                'status' => false,
                'msg' => 'wherenotset'
            ];
        }
        if (empty($data['data'])) {
            return [
                'status' => false,
                'msg' => 'datafieldsempty'
            ];
        }
        if (isset($data['data']['password'])) {
            if (empty($params['PASSWORD_NO_HASH'])) {
                $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_ARGON2I, [
                    'memory_cost' => CONFIG['argon_settings']['memory_cost'],
                    'time_cost' => CONFIG['argon_settings']['time_cost'],
                    'threads' => CONFIG['argon_settings']['threads']
                ]);
            }
        }
        $last_arr_elem = end($data['data']);
        $last_arr_elem_where = end($data['where']);
        $last_arr_elem_key = array_key_last($data['data']);
        $last_arr_elem_where_key = array_key_last($data['where']);
        foreach ($data['data'] as $column => $value) {
            $checkReq = $this->IsRequiredUpdate([
                'column' => $column,
                'value' => $value
            ]);
            if (!$checkReq['status']) {
                return $checkReq;
            }
            $cnarr = ':' . $column;
            $execarr[$cnarr] = $value;
            if ($last_arr_elem === $value && $last_arr_elem_key === $column) {
                $query .= $column . '=:' . $column . ' WHERE ';
                continue;
            }
            $query .= $column . '=:' . $column . ', ';
        }
        foreach ($data['where'] as $column => $value) {
            $cnarr = ':' . $column;
            if (isset($execarr[$cnarr])) {
                $cnarr = ':' . $column . '_two';
            }
            $execarr[$cnarr] = $value;
            if ($last_arr_elem_where === $value && $last_arr_elem_where_key === $column) {
                $query .= $column . '=' . $cnarr;
                continue;
            }
            $query .= $column . '=' . $cnarr . ' AND ';
        }

        $result = $this->conn->prepare($query);
        $returnval = $result->execute($execarr);
        if ($returnval) {
            return [
                'status' => true,
                'msg' => 'updatesuccesful',
                'rowcount' => $result->rowCount()
            ];
        }
        return [
            'status' => false,
            'msg' => 'unknownerror'
        ];
    }
    public function Delete($data)
    {
        if (empty($data['where'])) {
            return [
                'status' => false,
                'msg' => 'wherenotset'
            ];
        }
        $query = 'DELETE FROM ' . $this->pascalToSnake($this->mname) . ' WHERE ';
        $last_arr_elem_where = end($data['where']);
        foreach ($data['where'] as $column => $value) {
            $cnarr = ':' . $column;
            if (isset($execarr[$cnarr])) {
                $cnarr = ':' . $column . '_two';
            }
            $execarr[$cnarr] = $value;
            if ($last_arr_elem_where === $value) {
                $query .= $column . '=' . $cnarr;
                continue;
            }
            $query .= $column . '=' . $cnarr . ' AND ';
        }
        $result = $this->conn->prepare($query);
        $returnval = $result->execute($execarr);
        if ($returnval) {
            return [
                'status' => true,
                'msg' => 'deletesuccesful',
                'rowcount' => $result->rowCount()
            ];
        }
        return [
            'status' => false,
            'msg' => 'unknownerror'
        ];
    }

    /**
     * @param $data array
     * @description Returns all the "with" arguments on Select method
     */
    private function getAllWithArguments(array $data) 
    {
    
    }
    
    public function Select($data = [], $sub_model_name = "")
    {
        if (empty($data['columns'])) {
            $data['columns'] = '*';
        }
        $where_clause = '';
        $execarr = [];

        $data['where'] = isset($data['values']) ? $data['values'] : $data['where'] ?? null;

        if (isset($data['where'])) {
            $where_clause = ' WHERE ';
            $last_column = end($data['where']);
            foreach ($data['where'] as $selector => $parameters) {
                switch ($selector) {
                    case 'equals':
                        $data['where']['normal'] = $data['where']['equals'];
                        goto normal_case;
                    break;
                    case 'normal':
                        normal_case:
                        $last_arr_elem = end($data['where']['normal']);
                        foreach ($data['where']['normal'] as $column => $value) {
                            $name = ':' . $column . '_normal';
                            $execarr[$name] = $value;
                            if ($last_arr_elem === $value && $last_column === $data['where']['normal']) {
                                $where_clause .= $column . ' = ' . $name;
                                continue;
                            }
                            $where_clause .= $column . ' = ' . $name . ' AND ';
                        }
                        break;
                    case 'contains':
                        $last_arr_elem = end($data['where']['contains']);
                        foreach ($data['where']['contains'] as $column => $keyword) {
                            $name = ':' . $column;
                            $execarr[$name] = '%' . $keyword . '%';
                            if ($last_arr_elem === $keyword && $last_column === $data['where']['contains']) {
                                $where_clause .= $column . ' LIKE ' . $name;
                                continue;
                            }
                            $where_clause .= $column . ' LIKE ' . $name . ' AND ';
                        }
                        break;
                    case 'starts':
                        $last_arr_elem = end($data['where']['starts']);
                        foreach ($data['where']['starts'] as $column => $keyword) {
                            $name = ':' . $column;
                            $execarr[$name] = $keyword . '%';
                            if ($last_arr_elem === $keyword && $last_column === $data['where']['starts']) {
                                $where_clause .= $column . ' LIKE ' . $name;
                                continue;
                            }
                            $where_clause .= $column . ' LIKE ' . $name . ' AND ';
                        }
                        break;
                    case 'ends':
                        $last_arr_elem = end($data['where']['ends']);
                        foreach ($data['where']['ends'] as $column => $keyword) {
                            $name = ':' . $column;
                            $execarr[$name] = '%' . $keyword;
                            if ($last_arr_elem === $keyword && $last_column === $data['where']['ends']) {
                                $where_clause .= $column . ' LIKE ' . $name;
                                continue;
                            }
                            $where_clause .= $column . ' LIKE ' . $name . ' AND ';
                        }
                        break;
                    case 'bigger':
                        $last_arr_elem = end($data['where']['bigger']);
                        foreach ($data['where']['bigger'] as $column => $value) {
                            $name = ':' . $column . '_bigger';
                            $execarr[$name] = $value;
                            if ($last_arr_elem === $value && $last_column === $data['where']['bigger']) {
                                $where_clause .= $column . ' > ' . $name;
                                continue;
                            }
                            $where_clause .= $column . ' > ' . $name . ' AND ';
                        }
                        break;
                    case 'smaller':
                        $last_arr_elem = end($data['where']['smaller']);
                        foreach ($data['where']['smaller'] as $column => $value) {
                            $name = ':' . $column . '_smaller';
                            $execarr[$name] = $value;
                            if ($last_arr_elem === $value && $last_column === $data['where']['smaller']) {
                                $where_clause .= $column . ' < ' . $name;
                                continue;
                            }
                            $where_clause .= $column . ' < ' . $name . ' AND ';
                        }
                        break;
                    case 'in':
                        $last_arr_elem = end($data['where']['in']);
                        $last_arr_elem_key = key($data['where']['in']);
                        foreach ($data['where']['in'] as $column => $values) {
                            if (count($values) < 1) continue;
                            $placeholders = [];

                            // Create a placeholder for each value
                            foreach ($values as $i => $val) {
                                $ph = ':' . $column . '_in_' . $i;
                                $placeholders[] = $ph;
                                $execarr[$ph] = $val;
                            }

                            // Build the IN() clause
                            $in_clause = $column . ' IN (' . implode(', ', $placeholders) . ')';

                            if ($last_arr_elem_key === $column && $last_column === $data['where']['in']) {
                                // Last column special handling (no AND)
                                $where_clause .= $in_clause; // keep full IN() with parentheses
                                continue;
                            }

                            $where_clause .= $in_clause . ' AND ';
                        }
                    break;
                }
            }
        }

        $order_clause = ' ';
        if (isset($data['order'])) {
            $order_clause .= 'ORDER BY ';
            $last_arr_elem = end($data['order']);
            foreach ($data['order'] as $column => $order) {
                if ($last_arr_elem === $order) {
                    $order_clause .= $column . ' ' . $order;
                    continue;
                }
                $order_clause .= $column . ' ' . $order . ', ';
            }
        }

        $limit_clause = isset($data['limit']) ? ' LIMIT ' . $data['limit'] : ' LIMIT 50' ;

        if (isset($data['limit'])) {
            $limit_clause = ' LIMIT ' . $data['limit'];
        }
        if (isset($data['offset'])) {
            $limit_clause .= ' OFFSET ' . $data['offset'];
        }

        $mname = empty($sub_model_name) ? $this->pascalToSnake($this->mname) : $this->pascalToSnake($sub_model_name);

        $where_clause = count($execarr) < 1 ? ' ' : $where_clause;

        $query = <<<EOT
            SELECT {$data['columns']} FROM {$mname}{$where_clause}{$order_clause}{$limit_clause};
        EOT;

        $query = $this->conn->prepare($query);
        $query->execute($execarr);

        $results = $query->fetchAll(\PDO::FETCH_ASSOC);
        
        if (!isset($data['with'])) {
            return $results;
        }

        foreach ($data['with'] as $key => $settings) {
            if (count($results) < 1) {
                return $results;
            }

            $class_name = is_int($key) ? $settings : $key;
            $model_exploded = explode('\\', $class_name);
            $model_name = end($model_exploded);
            $this->CallModel($model_name, true);
            $model_instance = $this->submodels[$model_name]['model'];
            $model_rules = $this->submodels[$model_name]['rules'];

            if (!isset($model_rules['belongsTo'])) continue;

            $isClassArrayValue = in_array($this->model::class, $model_rules['belongsTo']);
            $isClassArrayKey = array_key_exists($this->model::class, $model_rules['belongsTo']) 
                && count($model_rules['belongsTo'][$this->model::class]) > 1;
            $hasOnlyRel = array_key_exists($this->model::class, $model_rules['belongsTo'])
                && array_key_exists('ref', $model_rules['belongsTo'][$this->model::class])
                && count($model_rules['belongsTo'][$this->model::class]) === 1;

            $defaultFields = [lcfirst($this->model_short_name) . '_id', 'id'];

            $connectingFields = match(true) {
                $isClassArrayValue => $defaultFields,
                $isClassArrayKey => [
                    $model_rules['belongsTo'][$this->model::class][0],
                    $model_rules['belongsTo'][$this->model::class][1],
                ],
                $hasOnlyRel => $defaultFields,
                default => null
            };

            if ($connectingFields === null) {
                throw new \Exception("Invalid connection fields between " . $this->model::class . " and " . $class_name);
            }

            if (!array_key_exists($connectingFields[1], $results[0])) {
                throw new \Exception("Invalid connection field between " . $this->model::class . " and " . $class_name);
            }

            $ids_of_parent = array_column($results, $connectingFields[1]);

            $settings_arr = [
                'where' => [
                    'in' => [
                        $connectingFields[0] => $ids_of_parent
                    ]
                ]
            ];

            if (is_array($settings)) {
                $settings_arr = array_merge_recursive($settings, $settings_arr);
            }

            $sub_results = $model_instance->Select($settings_arr);

            foreach ($results as &$row) {
                $related = array_filter($sub_results, fn($sub) =>
                    $sub[$connectingFields[0]] == $row[$connectingFields[1]]
                );
                $row[$model_name] = array_values($related);
            }
            unset($row);
        }
        return $results;
    }

    /*
     *  @param string $class_name 
     *  @param string $join_method
     * 
     *  @return 
     * 
     */
    public function SelectChildren($class_name, $join_method) {
        
    }
}
