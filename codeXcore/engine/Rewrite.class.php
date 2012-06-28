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

class Rewrite{
    private $X;
    private $urlQuery = array();
    
    public function __construct($X) {
        $this->X = $X;
        $urlString = $_SERVER['QUERY_STRING'];
        
        $urlString = str_replace(array('&', '='), '/', $urlString);

        if(substr($urlString, 0, 1)=='/'){
            $urlString = substr($urlString, 1);
        }
        
        if(substr($urlString, strlen($urlString)-1)=='/'){
            $urlString = substr($urlString, 0, strlen($urlString)-1);
        }
        
        $this->urlQuery = explode('/', $urlString);
    }
    
    static function exploreControllerSegment($controllerSegment){
        $docRoot = APP_ROOT;
        $module = APP_DEFAULT_MODULE;
        $controller = NULL;
        $controllerClassName = APP_DEFAULT_CONTROLLER_CLASS_NAME;
        $cs = explode('.', $controllerSegment);
        
        if(count($cs)==1 || count($cs)==3){
            if(count($cs)==1){
                $controller = $cs[0];
            }
            else{
                $docRoot = $cs[0];
                $module = $cs[1];
                $controller = $cs[2];
            }
            $controllerClassName = "\\".$docRoot."\\".MODULE_DIR."\\".$module."\\".CONTROLLER_DIR."\\".$controller."Controller";
        }
        else{
            throw new \LengthException(__METHOD__.": Invalid Controller Length on Request.");
        }

        return array("docRoot"=>$docRoot, "module"=>$module, "controllerClassName"=>$controllerClassName);
    }

    public function setRootModuleControllerAction(){
        $docRoot = APP_ROOT;
        $module = APP_DEFAULT_MODULE;
        $controllerClassName = APP_DEFAULT_CONTROLLER_CLASS_NAME;
        $action = APP_DEFAULT_ACTION_NAME;

        if(isset($this->urlQuery[0]) && $this->urlQuery[0]!=''){
            $cs = self::exploreControllerSegment($this->urlQuery[0]);
            $docRoot = $cs['docRoot'];
            $module = $cs['module'];
            $controllerClassName = $cs['controllerClassName'];
        }
        if(isset($this->urlQuery[1])){
            $action = $this->urlQuery[1];
        }

        $this->X->docRoot = $docRoot;
        $this->X->module = $module;
        $this->X->controllerClassName = $controllerClassName;
        $this->X->action = $action;
    }
    
    public function setXGet(){
        if(count($this->urlQuery)>2){
            for($i=1; $i<count($this->urlQuery); $i++){
                if(isset($this->urlQuery[$i*2])){
                    $var = $this->urlQuery[$i*2];
                    $value = $this->urlQuery[$i*2+1];
                }
                $this->X->G->$var = $value;
            }
        }
    }
}
?>
