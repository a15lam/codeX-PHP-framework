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

class Model{
    protected $X;
    protected $table;
    protected $id;
    protected $idField;
    
    public function __construct($X, $table, $id=0, $idField='f_id') {
        $this->X = $X;
        $this->table = $table;
        $this->id = $id;
        $this->idField = $idField;
    }
    
    public function __get($p){
        if(property_exists($this, $p)){
            return $this->$p;
        }
        else{
           $this->isIdSet();
           $sql = "select $p from $this->table where $this->idField = '$this->id'";
           $rs = $this->X->db->read($sql);
           return $rs[0][$p];
        }
    }
    
    protected function isIdSet(){
        if(!$this->id) throw new \UnexpectedValueException(__METHOD__.": Invalid Model ID");
    }
}
?>
