<?php
/******************************************************************************
* Copyright (c) 2012 Ariful Islam
* 
* Permission is hereby granted, free of charge, to any person obtaining
* a copy of this software and associated documentation files (the
* "Software"), to deal in the Software without restriction, including
* without limitation the rights to use, copy, modify, merge, publish,
* distribute, sublicense, and/or sell copies of the Software, and to
* permit persons to whom the Software is furnished to do so, subject to
* the following conditions:
* 
* The above copyright notice and this permission notice shall be
* included in all copies or substantial portions of the Software.
* 
* THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
* EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
* MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
* NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
* LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
* OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
* WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*******************************************************************************/
namespace X\engine;

class XPDO extends \PDO{
    private $X;
    
    public function __construct($X, $host, $user, $password, $db, $port='3306', $driverOptions=NULL){
        $this->X = $X;
        $dsn = "mysql:host=$host;dbname=$db;port=$port";
        parent::__construct($dsn, $user, $password, $driverOptions);
    }
    
    public function exec($foo){
        throw new \Exception(__METHOD__.": Direct use of exec is prohibited for security reasons ($foo)");
    }
    
    public function fieldExists($table, $field){
        $allFields = $this->getAllFields($table);
        if(in_array($field, $allFields)){
            return true;
        }
        else{
            return false;
        }
    }
    
    public function read($sql){
        $stm = parent::prepare($sql);
        $stm->execute();
        $result = $stm->fetchAll(\PDO::FETCH_ASSOC);
        return $result;
    }
    
    public function insert($table, $data){
        //check for user permission using $this->X->user->{permission field for insert}
        $sql = $this->constructInsert($table, $data);
        return parent::exec($sql);
    }
    
    public function update($table, $data, $whereClause){
        //check for user permission using $this->X->user->{permission field for update}
        $sql = $this->constructUpdate($table, $data, $whereClause);
        return parent::exec($sql);
    }
    
    public function delete($sql){
        //check for user permission using $this->X->user->{permission field for delete}
        return parent::exec($sql);
    }
    
    private function constructInsert($table, $data){
        $fields = array();
        $values = array();
        $table_fields = $this->getAllFields($table);
        foreach($data as $k => $v){
                if(in_array($k, $table_fields)){
                        $fields[]=$k;
                        //Strips slashes and adds slashes as necessary to the value
                        $values[]=addslashes(stripcslashes($v));
                }
        }
        if(!is_array($fields) || !is_array($values))
            throw new \Exception(__METHOD__.': Error - Fields and values must be sent as an array : '.get_class($this));

        $field_ct  = count($fields);
        $value_ct = count($values);

        if($field_ct != $value_ct)
            throw new \Exception(__METHOD__.': Error - Field count and value count do not match. : '.get_class($this));

        $query = "INSERT INTO `$table` (`";
        $query .= implode('`, `', $fields) . "`) VALUES ('";
        $query .= implode("', '", $values) . "');";

        $query = str_replace("'NOW()'", "NOW()", $query);
        $query = str_replace("'CURDATE()'", "CURDATE()", $query);
        $query = str_replace("'NULL'", "NULL", $query);

        return $query;
    }
    
    private function constructUpdate ($table, $data, $wheres){
        $fields = array();
        $values = array();
        $table_fields = $this->getAllFields($table);
        foreach($data as $k => $v){
                if(in_array($k, $table_fields)){
                        $fields[]=$k;
                        //Strips slashes and adds slashes as necessary to the value
                        $values[]=addslashes(stripcslashes($v));
                }
        }
        
        if(!is_array($fields) || !is_array($values))
            throw new \Exception(__METHOD__.': Error - Fields and values must be sent as an array : '.get_class($this));

        if($wheres !== false && strlen($wheres) < 1)
            throw new \Exception(__METHOD__.': Error - Where msut be implicitly set to false if it is to effect all entries in the table. : '.get_class($this));

        $field_ct  = count($fields);
        $value_ct = count($values);

        if($field_ct != $value_ct)
            throw new \Exception(__METHOD__.': Error - Field count and value count do not match : '.get_class($this));

        $query = "UPDATE `$table` set";

        for($x=0;$x<$field_ct;$x++){
            $queryset[] = "`{$fields[$x]}` = '{$values[$x]}'";
        }
        $query .= implode(', ', $queryset);

        if($wheres !== false)       $query .= " WHERE " . $wheres ;

        $query = str_replace("'NOW()'", "NOW()", $query);
        $query = str_replace("'NULL'", "NULL", $query);

        return $query;
    }
    
    private function getAllFields($table){
        $sql = "describe $table";
        $stm = parent::prepare($sql);
        $stm->execute();
        $result = $stm->fetchAll(\PDO::FETCH_ASSOC);
        $my_result = array();
        foreach($result as $rs){
                $my_result[] = $rs['Field'];
        }
        return $my_result;
    }
}
?>
