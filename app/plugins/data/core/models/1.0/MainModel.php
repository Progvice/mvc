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
    private $selectInChunkSize = 1000;

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

    private function getBelongsToFields(array $rules, string $parentClass, string $parentModelName)
    {
        if (!isset($rules['belongsTo'])) {
            return null;
        }

        foreach ($rules['belongsTo'] as $key => $settings) {
            if (is_int($key) && $settings === $parentClass) {
                return [lcfirst($parentModelName) . '_id', 'id'];
            }

            if ($key !== $parentClass) {
                continue;
            }

            if (isset($settings[0]) && isset($settings[1])) {
                return [$settings[0], $settings[1]];
            }

            return [lcfirst($parentModelName) . '_id', 'id'];
        }

        return null;
    }

    private function addColumnToSelect($columns, string $column)
    {
        if (empty($columns) || trim($columns) === '*') {
            return [$columns, false];
        }

        $selectedColumns = array_map('trim', explode(',', $columns));
        if (in_array($column, $selectedColumns, true)) {
            return [$columns, false];
        }

        $selectedColumns[] = $column;
        return [implode(', ', $selectedColumns), true];
    }
    
    public function Select($data = [], $sub_model_name = "")
    {
        if (empty($data['columns'])) {
            $data['columns'] = '*';
        }

        $hiddenColumns = [];
        if (isset($data['with'])) {
            foreach ($data['with'] as $key => $settings) {
                $class_name = is_int($key) ? $settings : $key;
                $model_exploded = explode('\\', $class_name);
                $model_name = end($model_exploded);
                $this->CallModel($model_name, true);
                $model_rules = $this->submodels[$model_name]['rules'];

                $childFields = $this->getBelongsToFields($model_rules, $this->model::class, $this->model_short_name);
                $parentFields = $this->getBelongsToFields($this->rules, $class_name, $model_name);
                $currentColumn = $childFields[1] ?? $parentFields[0] ?? null;

                if ($currentColumn === null) {
                    continue;
                }

                [$data['columns'], $added] = $this->addColumnToSelect($data['columns'], $currentColumn);
                if ($added) {
                    $hiddenColumns[] = $currentColumn;
                }
            }
        }

        $mname = empty($sub_model_name) ? $this->pascalToSnake($this->mname) : $this->pascalToSnake($sub_model_name);
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

        if (isset($data['has'])) {
            foreach ($data['has'] as $key => $conditions) {
                $class_name = is_int($key) ? $conditions : $key;
                $model_exploded = explode('\\', $class_name);
                $model_name = end($model_exploded);
                $this->CallModel($model_name, true);
                $model_instance = $this->submodels[$model_name]['model'];
                $model_rules = $this->submodels[$model_name]['rules'];

                $childFields = $this->getBelongsToFields($model_rules, $this->model::class, $this->model_short_name);
                $parentFields = $this->getBelongsToFields($this->rules, $class_name, $model_name);

                if ($childFields !== null) {
                    $connectingFields = [
                        'current' => $childFields[1],
                        'related' => $childFields[0],
                    ];
                } else if ($parentFields !== null) {
                    $connectingFields = [
                        'current' => $parentFields[0],
                        'related' => $parentFields[1],
                    ];
                } else {
                    $connectingFields = null;
                }

                if ($connectingFields === null) {
                    throw new \Exception("Invalid connection fields between " . $this->model::class . " and " . $class_name);
                }

                $related_table = $this->pascalToSnake($model_instance->mname);
                $has_conditions = [
                    $related_table . '.' . $connectingFields['related'] . ' = ' . $mname . '.' . $connectingFields['current']
                ];

                if (!is_array($conditions)) {
                    $conditions = [];
                }

                foreach ($conditions as $field => $value) {
                    $placeholder = ':has_' . count($execarr) . '_' . preg_replace('/[^a-zA-Z0-9_]/', '_', $field);
                    $execarr[$placeholder] = $value;
                    $has_conditions[] = $related_table . '.' . $field . ' = ' . $placeholder;
                }

                $has_clause = 'EXISTS (SELECT 1 FROM ' . $related_table . ' WHERE ' . implode(' AND ', $has_conditions) . ')';
                if (empty(trim($where_clause))) {
                    $where_clause = ' WHERE ' . $has_clause;
                } else {
                    $where_clause .= ' AND ' . $has_clause;
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

        $limit_clause = !empty($data['without_limit']) ? ' ' : ' LIMIT 50' ;

        if (isset($data['limit'])) {
            $limit_clause = ' LIMIT ' . $data['limit'];
        }
        if (isset($data['offset'])) {
            $limit_clause .= ' OFFSET ' . $data['offset'];
        }

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

            $childFields = $this->getBelongsToFields($model_rules, $this->model::class, $this->model_short_name);
            $parentFields = $this->getBelongsToFields($this->rules, $class_name, $model_name);

            if ($childFields !== null) {
                $connectingFields = [
                    'current' => $childFields[1],
                    'related' => $childFields[0],
                ];
            } else if ($parentFields !== null) {
                $connectingFields = [
                    'current' => $parentFields[0],
                    'related' => $parentFields[1],
                ];
            } else {
                $connectingFields = null;
            }

            if ($connectingFields === null) {
                throw new \Exception("Invalid connection fields between " . $this->model::class . " and " . $class_name);
            }

            if (!array_key_exists($connectingFields['current'], $results[0])) {
                throw new \Exception("Invalid connection field between " . $this->model::class . " and " . $class_name);
            }

            $ids_of_parent = array_values(array_unique(array_column($results, $connectingFields['current'])));

            if (count($ids_of_parent) < 1) {
                foreach ($results as &$row) {
                    $row[$model_name] = [];
                }
                unset($row);
                continue;
            }

            $settings_arr = [
                'where' => [
                    'in' => [
                        $connectingFields['related'] => $ids_of_parent
                    ]
                ]
            ];

            if (is_array($settings)) {
                $settings_arr = array_merge_recursive($settings, $settings_arr);
            }

            if (!isset($settings_arr['limit'])) {
                $settings_arr['without_limit'] = true;
            }

            $hiddenRelatedColumn = null;
            [$settings_arr['columns'], $added] = $this->addColumnToSelect($settings_arr['columns'] ?? '*', $connectingFields['related']);
            if ($added) {
                $hiddenRelatedColumn = $connectingFields['related'];
            }

            $sub_results = [];
            foreach (array_chunk($ids_of_parent, $this->selectInChunkSize) as $ids_chunk) {
                $chunk_settings_arr = $settings_arr;
                $chunk_settings_arr['where']['in'][$connectingFields['related']] = $ids_chunk;
                $sub_results = array_merge($sub_results, $model_instance->Select($chunk_settings_arr));
            }

            $relatedByKey = [];
            foreach ($sub_results as $sub) {
                $key = $sub[$connectingFields['related']];

                if ($hiddenRelatedColumn !== null) {
                    unset($sub[$hiddenRelatedColumn]);
                }

                $relatedByKey[$key][] = $sub;
            }

            foreach ($results as &$row) {
                $row[$model_name] = $relatedByKey[$row[$connectingFields['current']]] ?? [];
            }
            unset($row);
        }

        foreach ($results as &$row) {
            foreach ($hiddenColumns as $column) {
                unset($row[$column]);
            }
        }
        unset($row);

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
