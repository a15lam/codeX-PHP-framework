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

class Router{
    private $X;
    private $myControllerClassName;
    private $myActionName;
    
    public function __construct(X $X) {
        $this->X = $X;
        $this->myControllerClassName = $this->X->controllerClassName;
        $this->myActionName = $this->X->action;
    }
    
    private function checkRequestPrivilege(){
        $myRequest = $this->myControllerClassName."::".$this->myActionName;
        $openRequests = unserialize(OPEN_CONTROLLER_ACTION);
        
        if(!$_SESSION['userID'] && !in_array($myRequest, $openRequests)){
            $this->X->controllerClassName = APP_DEFAULT_CONTROLLER_CLASS_NAME;
            $this->X->action = APP_DEFAULT_ACTION_NAME;
            $this->myControllerClassName = APP_DEFAULT_CONTROLLER_CLASS_NAME;
            $this->myActionName = APP_DEFAULT_ACTION_NAME;
        }
    }
    
    public function load(){
        $this->checkRequestPrivilege();
        $action = $this->myActionName;
        $className = $this->myControllerClassName;
        
        $myController = new $className($this->X);

        if(!is_callable(array($myController, $action))){
            throw new \DomainException(__METHOD__.": Failed to call action $className::$action");
        }

        $myController->$action();
    }
}
?>
