<?php

namespace Core\App\Models;
class MainModel {
    protected $model;
    protected $mname;
    protected $conn;
    public function CallModel($name) {
        \plugin::load('db');
        $this->conn = \Core\App\DB::Connect();
        $name = ucfirst($name);
        require MODEL_PATH . '/' . $name .'.php';
        $this->mname = $name;
        $modelname = 'Core\App\Models\\' . $name;
        $this->model = new $modelname;
    }
    /*
     *  @return true||false - False means that datatype is not correct
     * 
     */
    public function CheckDataType($data) {
        $rvalue = true;
        foreach($this->model->rules as $name => $rules) {
            if(isset($rules['type'])) {
                switch($rules['type']) {
                    case 'string':
                        if(!is_string($data[$name])) {
                            $rvalue = false;
                        }
                    break;
                    case 'array':
                        if(!is_array($data[$name])) {
                            $rvalue = false;
                        }
                    break;
                    case 'int':
                        if(!is_int($data[$name])) {
                            $rvalue = false;
                        }
                    break;
                    case 'decimal':
                        if(!is_float($data[$name])) {
                            $rvalue = false;
                        }
                    break;
                    case 'date': 
                        
                    break;
                    default:
                        return 'Not proper datatype';
                    break;
                }
            }
        }
        return $rvalue;
    }
    /*
     *  @return true||false - False means that data length is not correct
     * 
     */
    public function CheckLength($data) {
        $rvalue = true;
        foreach($this->model->rules as $name => $rules) {
            if(isset($rules['length'])) {
                if(strlen($data[$name]) > $rules['length']) {
                    $rvalue = false;
                    continue;
                }
            }
        }
        return $rvalue;
    }
    /*
     *  IsRequired() 
     *  @return true||false - False means that data is not set.
     * 
     */
    public function IsRequired($data) {
        $rvalue = true;
        foreach($this->model->rules as $name => $rules) {
            if(isset($rules['required'])) {
                if(empty($data[$name])) {
                    $rvalue = false;
                    continue;
                }
            }
        }
        return $rvalue;
    }
    public function IsUnique($data) {
        $rvalue = true;
        foreach($this->model->rules as $name => $rules) {
            if(is_array($rules)) {
                foreach($rules as $rname => $rule) {
                    if($rname === 'unique' && $rule === true) {
                        $boolval = $this->Select([
                            'value_field' => $name,
                            'value' => $data[$name]
                        ]);
                        if(!empty($boolval)) {
                            $rvalue = false;
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
    public function Insert($data) {
        if($this->CheckDataType($data) === false) {
            return 'All datatypes are not correct';
        }
        else if($this->CheckLength($data) === false) {
            return 'Length is not correct in some data.';
        }
        else if($this->IsRequired($data) === false) {
            return 'Required datafield is empty.';
        }
        else if($this->IsUnique($data) === false) {
            return 'Value already exists!';
        }
        $query = 'INSERT INTO ' . $this->mname . ' ';
        $columns = '(';
        $values = '(';
        $last_arr_elem = end($data);
        $execarr = [];
        foreach($data as $column_name => $column_value) {
            $cnarr = ':' . $column_name;
            $execarr[$cnarr] = $column_value;
            if($last_arr_elem === $column_value) {
                $columns .= $column_name . ') VALUES ';
                $values .= ':' . $column_name . ');';
                continue;
            }
            $columns .= $column_name . ', ';
            $values .= ':' . $column_name . ', ';
        }
        $query .= $columns . $values;
        $result = $this->conn->prepare($query);
        $result->execute($execarr);
        return $result;
    }
    /*
     *  Select()
     *  @param  $data    array
     * 
     *      @example
     *          [
     *              'columns' => 'product_name, product_price'  -   Column names in database. If you want to fetch all don't set this. 
     * 
     * 
     *              'value' => 2                                -   This value is used to find column that corresponds with models primary key.
     *                                                              Looks automatically for primary key in model rules. If one is not set
     *                                                              this method returns null.
     * 
     *              'value_field => 'product_name'              -   Change column that is used to find data
     * 
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
     *      atleast if developer does not want to fetch everything.
     * 
     */
    public function Select($data = []) {
        $fetch_all = false;
        if(empty($data['columns'])) {
            $data['columns'] = '*';
        }
        $where_clause = '';
        $execarr = [];
        if(isset($data['value'])) {
            if(isset($data['value_field'])) {
                $where_clause = ' WHERE ' . $data['value_field'] . ' = :' . $data['value_field'];
                $execarr = [
                    ':' . $data['value_field'] => $data['value']
                ];
            }
            else {
                $where_clause = ' WHERE ' . $this->model->rules['primary_key'] . '= :' . $this->model->rules['primary_key'];
                $execarr = [
                    ':' . $this->model->rules['primary_key'] => $data['value']
                ];
            }
        }
        else if(isset($data['values'])) {
            $last_arr_elem = end($data['values']);
            $where_clause = ' WHERE ';
            $execarr = [];
            foreach($data['values'] as $column => $value) {
                $name = ':' . $column;
                $execarr[$name] = $value;
                if($last_arr_elem === $value) {
                    $where_clause .= $column . ' = :' . $column;
                    continue;
                }
                $where_clause .= $column . ' = :' . $column . ' AND ';
            }
        }
        else {
            $fetch_all = true;
        }
        $order_clause = ' ';
        if(isset($data['order'])) {
            $order_clause .= 'ORDER BY ';
            $last_arr_elem = end($data['order']);
            foreach($data['order'] as $column => $order) {
                if($last_arr_elem === $order) {
                    $order_clause .= $column . ' ' . $order;
                    continue;
                }
                $order_clause .= $column . ' ' . $order . ', ';
            }
        }
        $limit_clause = '';
        if(isset($data['limit'])) {
            $limit_clause = ' LIMIT ' . $data['limit'];
        }
        $query = <<<EOT
            SELECT {$data['columns']} FROM {$this->mname}{$where_clause}{$order_clause}{$limit_clause};
        EOT;
        $query = $this->conn->prepare($query);
        $query->execute($execarr);
        if($fetch_all === true) {
            return $query->fetchAll(\PDO::FETCH_ASSOC);
        }
        return $query->fetchAll(\PDO::FETCH_ASSOC);
    }
}
?>