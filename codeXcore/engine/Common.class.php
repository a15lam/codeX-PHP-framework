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

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class Common{
    protected $X;
    
    public function __construct($X) {
        $this->X = $X;
    }
    
    public function getRequest($field){
        if($this->X->P->$field) return $this->X->P->$field;
        if($this->X->G->$field) return $this->X->G->$field;
    }
    
    static function isJson($val) {
        if((substr($val, 0, 1) == '{' && substr($val, strlen($val) - 1, 1) == '}') || (substr($val, 0, 1) == '[' && substr($val, strlen($val) - 1, 1) == ']')) {
            json_decode($val);
            if(json_last_error() == JSON_ERROR_NONE) {
                return true;
            }
            else {
                return false;
            }
        }
        else {
            return false;
        }
    }
    
    /**
     * This method creates an Ext.data.JsonStore config with data that's
     * suitable for a combobox (drop down list).
     *
     * @param array $records Array of data
     * @param string $valueField Name of the field that holds the value
     * @param string $displayField Name of the field that holds the text to display on the drop down list
     * @param string $visibleField (Optional) Name of the field that indicates whether an option on the list is selectable or not. Defaults to f_visible
     * @param boolean $includeSelectOne (Optional) Setting this to true will add a 'Select One' option to the top of the list. Defaults to true.
     * @param string $defaultField (Optional) The field in the model's table that stores whether or not the record is the default value. Defaults to 'f_defualt'
     * @return string Ext.data.JsonStore config string
     */
    public function generateDropDownJsonStore($records, $valueField, $displayField, $visibleField='f_visible', $includeSelectOne=1, $defaultField = 'f_default') {
        $config = array(
            "valueField" => $valueField,
            "displayField" => $displayField,
            "includeSelectOne" => $includeSelectOne,
            "visibleField" => $visibleField,
            "defaultField" => $defaultField
        );

        return $this->generateJsonStore($records, $config);
    }

    /**
     * This method creates an Ext.data.JsonStore config with data.
     * Example usage:
     * <code>
     * $config = array(
     *      "valueField"=>'f_value',
     *      "displayField"=>'f_description',
     *      "visibleField"=>'f_visible',
     *      "includeSelectOne"=>true
     * );
     * </code>
     *
     * @param array $records Array of data
     * @param array $config (Optional) Config option array that supports all Ext.data.JsonStore config property + more (see example usage). Defaults to empty array.
     * @return string Ext.data.JsonStore config string
     */
    public function generateJsonStore($records, $config=array(), $pagingTotal = 0) {
        //Setting some default properties
        if(!isset($config['includeSelectOne']))
            $config['includeSelectOne'] = 1;
        if(!isset($config['visibleField']))
            $config['visibleField'] = 'f_visible';

        $metaData = array();
        $metaData['idProperty'] = 'id';
        $metaData['root'] = 'rows';
        $metaData['totalProperty'] = 'results';
        $metaData['successProperty'] = 'success';

        //This will override any default meta data property
        //that's present in the passed in $config array
        foreach($metaData as $k => $v) {
            if(isset($config[$k])) {
                $metaData[$k] = $config[$k];
            }
        }

        if(!isset($config['fields'])) {
            $fields = array();
            if(isset($config['valueField']) && isset($config['displayField'])) {
                array_push($fields, array(
                    'name' => 'valueField',
                    'mapping' => $config['valueField'],
                    'type' => 'string'
                    ), array(
                    'name' => 'displayField',
                    'mapping' => $config['displayField'],
                    'type' => 'string'
                    )
                );
                if(isset($config['defaultField'])) {
                    array_push($fields, array(
                        'defaultField' => $config['defaultField']
                    ));
                }
                if($config['includeSelectOne']) {
                    array_unshift($records, array($config['valueField'] => 0, $config['displayField'] => 'Select One', $config['visibleField'] => 1));
                }
            }

            if(isset($config['visibleField'])) {
                if(is_array($config['visibleField'])){
                    $banField = $config['visibleField']['field'];
                    $banMsg = $config['visibleField']['msg'];
                }
                else{
                    $banField = $config['visibleField'];
                    $banMsg = 'This option is unavailable.';
                }

                $fields[] = 'banned';
                $fields[] = 'banReason';
                $visibleField = $banField;

                for($i = 0; $i < count($records); $i++) {
                    if($records[$i][$visibleField] == 'N' || $records[$i][$visibleField] == '0') {
                        $records[$i]['banned'] = true;
                        $records[$i]['banReason'] = $banMsg;
                    }
                    else {
                        $records[$i]['banned'] = false;
                        $records[$i]['banReason'] = '';
                    }
                }
            }

            //If a records has more than 1 row then it's best
            //to choose the second one. Because the first one may
            //have a 'Select One' row.
            if(count($records) > 1) {
                $row = $records[1];
            }
            else {
                $row = $records[0];
            }

            $j = count($fields);

            //These are the fields that's already on the fields list
            $keyExceptions = array(
                $config['valueField'],
                $config['displayField'],
                'banned',
                'banReason'
            );

            //Creating additional fields based on what's availabe on a record row
            if(count($row)>0){
                foreach($row as $k => $v) {
                    $type = 'string';
                    if(!in_array($k, $keyExceptions)) {
                        if(preg_match('/^\d{4}\-\d{2}\-\d{2}$/', $row[$k]) || $row[$k]=='0000-00-00') {
                            $type = 'date';
                            $fields[$j]['dateFormat'] = 'Y-m-d';
                        }
                        $fields[$j]['name'] = $k;
                        $fields[$j]['type'] = $type;
                        $j++;
                    }
                }
            }
            $metaData['fields'] = $fields;
        }
        else {
            $metaData['fields'] = $config['fields'];
        }
        $pagingTotal = ($pagingTotal > 0) ? $pagingTotal : count($records);
        return json_encode(array('metaData' => $metaData, 'success' => true, 'results' => $pagingTotal, 'rows' => $records));
    }
}
?>
