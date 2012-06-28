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

class Template{
    protected $X;
    protected $vars = array();
    
    public function __construct($X) {
        $this->X = $X;
    }
    
    public function __set($index, $value){
        $this->vars[$index] = $value;
    }

    public function __get($index){
        if(isset($index)){
            return $this->vars[$index];
        }
    }

    private function getViewFile($view){
        if(!$view) return NULL;
        
        $viewName = str_replace("\\", DIRECTORY_SEPARATOR, $view);
            
        if($this->X->docRoot==APP_ROOT){
            $rootPath = APP_PATH;
        }
        else{
            $rootPath = X_PATH;
        }

        if(strpos($view, '.htm')){
            $viewFile = $rootPath.MODULE_DIR.DIRECTORY_SEPARATOR.$this->X->module.DIRECTORY_SEPARATOR.VIEW_DIR.DIRECTORY_SEPARATOR.$viewName;
        }
        else{
            $viewFile = $rootPath.MODULE_DIR.DIRECTORY_SEPARATOR.$this->X->module.DIRECTORY_SEPARATOR.VIEW_DIR.DIRECTORY_SEPARATOR.$viewName.'.v.php';
        }

        if(!file_exists($viewFile)){
            throw new \DomainException(__METHOD__.": Failed to load view - $viewFile");
        }
        
        return $viewFile;
    }
    
    public function show($config=array()) {
        if(!is_array($config)){
            if(strpos($config, '.htm')){
                $htmlFile = $this->getViewFile($config);
                require($htmlFile);
            }
        }
        else{
            // Make data suitable for showing on the from. example 2000-10-10 to 10/10/2000
            $this->makeDataForForm($this->vars);
            foreach ($this->vars as $key => $value){
                $$key = $value;
            }

            $__v_viewFile = $this->getViewFile($config['view_file']);
            $__v_pageTitle = $config['title'];
            $__v_cssFiles = (is_array($config['css_files']))? $config['css_files'] : array();
            $__v_jsFiles = (is_array($config['js_files']))? $config['js_files'] : array();
            $__v_includeFramework = ($config['no_framework'])? false : true;

            if($config['output_type']=='json'){
                if(is_array($config['json_data'])){
                    echo json_encode($config['json_data']);
                }
                else if(Common::isJson($config['json_data'])){
                    echo $config['json_data'];
                }
                else{
                    throw new \DomainException(__METHOD__.": Invalid JSON data supplied.");
                }
            }
            else{
                if(isset($config['base_view'])){
                    $baseView = $config['base_view'];
                }
                else{
                    $baseView = BASE_VIEW_FILE;
                }

                if(!file_exists($baseView)){
                    throw new \DomainException(__METHOD__.": Base view file ".$baseView." not found.");
                }
                require($baseView);
            }
        }
    }
    
    public function makeDataForForm(&$myvars){
        foreach($myvars as $key => $value){
            if(is_array($value)){
                $this->makeDataForForm($myvars[$key]);
            }
            else{
                if(is_string($value) && preg_match('/^\d{4}\-\d{2}\-\d{2}$/', $value)){
                    $value = date('mm/dd/yyyy', strtotime($value));
                    $myvars[$key] = $value;
                }
            }
        }
    }
    
    
    public function setExtJSVars($nameSpace, $data, $zeroValid=false){
        $vars = "Ext.ns('".$nameSpace."');";
        if(is_array($data) && count($data) > 0) {
            foreach($data as $k => $v) {
                if(!$zeroValid && $v == '0') {
                    $v = "";
                }
                if(\X\engine\Common::isJson($v)) {
                    $vars .= $nameSpace.".".$k." = ".$v.";\n";
                }
                else {
                    if( strpos($v, '\'') > 0) {
                        $vars .= $nameSpace.".".$k.' = "'.addslashes($v).'\";'."\n";
                    }
                    else {
                        $vars .= $nameSpace.".".$k." = '".$v."';\n";
                    }
                }
            }
        }
        $this->jsVars = $vars;
    }
}
?>
