<?php

namespace Core\App\Models;

use Core\App\DB;
use \Plugin;

class MainModel
{
    protected $model;
    protected $mname;
    protected $conn;
    protected $rules;
    public function CallModel($name)
    {
        Plugin::load('db');
        $this->conn = DB::Connect();
        $name = ucfirst($name);
        if (!file_exists(MODEL_PATH . '/' . $name . '.php')) {
            return [
                'status' => false,
                'msg' => 'Model not found'
            ];
        }
        require_once MODEL_PATH . '/' . $name . '.php';
        $modelname = 'Core\App\Models\\' . $name;
        $this->model = new $modelname;
        $this->rules = $this->model->rules;
        if (isset($this->model->rules['table'])) {
            $this->mname = $this->model->rules['table'];
        } else {
            $this->mname = $name;
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

    public function GetRequiredFields()
    {
        $requiredFields = [];

        if (empty($this->rules)) {
            return [
                'status' => false,
                'msg' => 'This action requires use of model'
            ];
        }

        foreach ($this->rules as $field => $ruleset) {
            if (isset($ruleset['required']) && $ruleset['required'] === true) {
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
            if (!isset($ruleset['type'])) continue;
            $fields[] = $field;
        }
        return $fields;
    }
    public function LoadModelRules()
    {
        return $this->rules;
    }
    /*  
     *  
     *  
     */
    public function BelongsTo() {}
    public function HasMany() {}
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
            if (!isset($rules['type'])) continue;
            if (!isset($rules[$name])) continue;

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
            if (!isset($rules['length'])) continue;
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

            if (!isset($rules['required'])) continue;
            if (!$rules['required']) continue;

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
            if (isset($data[$name]) && is_array($rules)) {
                foreach ($rules as $rname => $rule) {
                    if ($rname === 'unique' && $rule === true) {
                        $boolval = $this->Select([
                            'values' => [
                                'normal' => [
                                    $name => $data[$name]
                                ]
                            ]
                        ]);
                        if (!empty($boolval)) {
                            $msg_string = $name . 'exists';

                            if (isset(LANG[$msg_string])) {
                                $msg = LANG[$msg_string];
                            } else {
                                $msg = $msg_string;
                            }
                            $rvalue = [
                                'status' => false,
                                'msg' => $msg
                            ];
                        }
                    }
                }
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
                'memory_cost' => CONFIG['argon_settings']['memory_cost'],
                'time_cost' => CONFIG['argon_settings']['time_cost'],
                'threads' => CONFIG['argon_settings']['threads']
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
    /*
     *  Update()
     *  @param  $data   $array
     * 
     *  @example
     *      TABLE: Users
     *      [
     *          'where' => [
     *              'uuid' => '12' 
     *          ],
     *          'data' => [
     *              'username' => 'newusername',
     *              'email' => 'newemail@email.com'
     *          ]
     *      ]
     * 
     *      This would result in this kind of SQL query:
     * 
     *          UPDATE Users SET username=:username, email=:email WHERE uuid=:uuid
     * 
     *      And after preparing this query Update function will send all values for execution.
     *      
     * 
     *  
     */
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
    /*
     *  Select()
     *  @param  $data    array
     * 
     *      @example
     *          [
     *              'columns' => 'product_name, product_price'  -   Column names in database. If you want to fetch all columns don't set this. 
     *
     *              'values => [                                -   Set multiple criterias for finding data (ex. age = 18 AND name = "John").
     *                  'product_name' => 'computer',               "product_name" acts as column and 'computer' acts as value to search for. 
     *                  'product_id' => 24                          
     *              ]
     *              'limit' => 50                               -   Limit how many records will be fetched from database
     *              'order' => [                                -   Set order. 
     *                  'column_name' => 'DESC',
     *                  'column_name2' => 'ASC'
     *              ]
     *          ]
     * 
     *      If $data is let empty this script will fetch everything without any limits. Suggestion is that developer should use 'limit'
     *      if developer does not want to fetch everything.
     * 
     */
    public function Select($data = [])
    {
        $fetch_all = false;
        if (empty($data['columns'])) {
            $data['columns'] = '*';
        }
        $where_clause = '';
        $execarr = [];

        if (isset($data['values'])) {
            $where_clause = ' WHERE ';
            $execarr = [];
            $last_column = end($data['values']);
            foreach ($data['values'] as $selector => $parameters) {
                switch ($selector) {
                    case 'normal':
                        $last_arr_elem = end($data['values']['normal']);
                        foreach ($data['values']['normal'] as $column => $value) {
                            $name = ':' . $column . '_normal';
                            $execarr[$name] = $value;
                            if ($last_arr_elem === $value && $last_column === $data['values']['normal']) {
                                $where_clause .= $column . ' = ' . $name;
                                continue;
                            }
                            $where_clause .= $column . ' = ' . $name . ' AND ';
                        }
                        break;
                    case 'contains':
                        $last_arr_elem = end($data['values']['contains']);
                        foreach ($data['values']['contains'] as $column => $keyword) {
                            $name = ':' . $column;
                            $execarr[$name] = '%' . $keyword . '%';
                            if ($last_arr_elem === $keyword && $last_column === $data['values']['contains']) {
                                $where_clause .= $column . ' LIKE ' . $name;
                                continue;
                            }
                            $where_clause .= $column . ' LIKE ' . $name . ' AND ';
                        }
                        break;
                    case 'starts':
                        $last_arr_elem = end($data['values']['starts']);
                        foreach ($data['values']['starts'] as $column => $keyword) {
                            $name = ':' . $column;
                            $execarr[$name] = $keyword . '%';
                            if ($last_arr_elem === $keyword && $last_column === $data['values']['starts']) {
                                $where_clause .= $column . ' LIKE ' . $name;
                                continue;
                            }
                            $where_clause .= $column . ' LIKE ' . $name . ' AND ';
                        }
                        break;
                    case 'ends':
                        $last_arr_elem = end($data['values']['ends']);
                        foreach ($data['values']['ends'] as $column => $keyword) {
                            $name = ':' . $column;
                            $execarr[$name] = '%' . $keyword;
                            if ($last_arr_elem === $keyword && $last_column === $data['values']['ends']) {
                                $where_clause .= $column . ' LIKE ' . $name;
                                continue;
                            }
                            $where_clause .= $column . ' LIKE ' . $name . ' AND ';
                        }
                        break;
                    case 'bigger':
                        $last_arr_elem = end($data['values']['bigger']);
                        foreach ($data['values']['bigger'] as $column => $value) {
                            $name = ':' . $column . '_bigger';
                            $execarr[$name] = $value;
                            if ($last_arr_elem === $value && $last_column === $data['values']['bigger']) {
                                $where_clause .= $column . ' > ' . $name;
                                continue;
                            }
                            $where_clause .= $column . ' > ' . $name . ' AND ';
                        }
                        break;
                    case 'smaller':
                        $last_arr_elem = end($data['values']['smaller']);
                        foreach ($data['values']['smaller'] as $column => $value) {
                            $name = ':' . $column . '_smaller';
                            $execarr[$name] = $value;
                            if ($last_arr_elem === $value && $last_column === $data['values']['smaller']) {
                                $where_clause .= $column . ' < ' . $name;
                                continue;
                            }
                            $where_clause .= $column . ' < ' . $name . ' AND ';
                        }
                        break;
                }
            }
        } else {
            $fetch_all = true;
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
        $limit_clause = '';
        if (isset($data['limit'])) {
            $limit_clause = ' LIMIT ' . $data['limit'];
        }
        if (isset($data['offset'])) {
            $limit_clause .= ' OFFSET ' . $data['offset'];
        }

        $mname = $this->pascalToSnake($this->mname);

        $query = <<<EOT
            SELECT {$data['columns']} FROM {$mname}{$where_clause}{$order_clause}{$limit_clause};
        EOT;
        $query = $this->conn->prepare($query);
        $query->execute($execarr);
        if ($fetch_all === true) {
            return $query->fetchAll(\PDO::FETCH_ASSOC);
        }
        return $query->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function SelectWithJoin($data)
    {
        if (empty($data['tables']['owner'])) {
            return [
                'status' => false,
                'msg' => 'SQL Error: Owner not set'
            ];
        }
        if (empty($data['tables']['servant'])) {
            return [
                'status' => false,
                'msg' => 'SQL Error: Servant not set'
            ];
        }
        if (empty($data['columns'])) {
            $data['columns'] = '*';
        }
        if (empty($data['reverse'])) {
            $data['reverse'] = false;
        }
        $this->CallModel($data['tables']['owner']);
        $owner = $this->model;
        $this->CallModel($data['tables']['servant']);
        $servant = $this->model;
        $connections = [
            false,
            false
        ];
        $owner_field = '';
        $servant_field = '';
        foreach ($owner->rules['connections'] as $key => $value) {
            if ($key === $data['tables']['servant']) {
                $servant_field = $value['field'];
                $connections[0] = true;
                break;
            }
        }
        foreach ($servant->rules['connections'] as $key => $value) {
            if ($key === $data['tables']['owner']) {
                $owner_field = $value['field'];
                $connections[1] = true;
                break;
            }
        }
        if (!$connections[0] || !$connections[1]) {
            return [
                'status' => false,
                'msg' => 'invalidtableconnection'
            ];
        }
        $join_mode = '';
        switch ($data['mode']) {
            case 'innerjoin':
                $join_mode = 'INNER JOIN';
                break;

            case 'leftjoin':
                $join_mode = 'LEFT JOIN';
                break;

            case 'rightjoin':
                $join_mode = 'RIGHT JOIN';
                break;
            default:
                $join_mode = 'INNER JOIN';
                break;
        }
        $where_clause = '';
        $execarr = [];
        if (isset($data['where'])) {
            $where_clause = $where_clause . ' WHERE ';
            foreach ($data['where'] as $tablename => $table) {
                foreach ($table as $column => $value) {
                    $name = ':' . $column;
                    $execarr[$name] = $value;

                    if ($data['tables']['owner'] === $tablename) {
                        $where_clause = $where_clause . $data['tables']['owner'] . '.' . $column . ' = ' . $name;
                    } else {
                        $where_clause = $where_clause . $data['tables']['servant'] . '.' . $column . ' = ' . $name;
                    }
                }
            }
        }
        $parentn = $owner->rules['table'];
        $childn = $servant->rules['table'];
        if ($data['reverse']) {
            $query = <<<EOT
            SELECT {$data['columns']} FROM {$childn} {$join_mode} {$parentn}
             ON {$childn}.{$servant_field} = {$parentn}.{$owner_field}
             {$where_clause}
            EOT;
        } else {
            $query = <<<EOT
            SELECT {$data['columns']} FROM {$parentn} {$join_mode} {$childn}
             ON {$parentn}.{$owner_field} = {$childn}.{$servant_field}
             {$where_clause}
            EOT;
        }
        $query = $this->conn->prepare($query);
        $query->execute($execarr);
        return $query->fetchAll(\PDO::FETCH_ASSOC);
    }
}
