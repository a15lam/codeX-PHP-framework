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
function xClassLoader($className){    
    $className = str_replace("\\", DIRECTORY_SEPARATOR, $className);
    $cnp = explode(DIRECTORY_SEPARATOR, $className);
    
    if($cnp[0]=='X' || $cnp[0]=='codeXcore'){
        array_splice($cnp, 0, 1);
        $file = X_PATH.join(DIRECTORY_SEPARATOR, $cnp).".class.php";
    }
    else if($cnp[0]=='App' || $cnp[0]=='app'){
        array_splice($cnp, 0, 1);
        $file = APP_PATH.join(DIRECTORY_SEPARATOR, $cnp).".class.php";
    }
    else{
        $file = join(DIRECTORY_SEPARATOR, $cnp).".class.php";
    }
    
    if(!file_exists($file)){
        throw new \DomainException(__METHOD__.": Failed to load file - ".$file." (".$className.")");
    }
    
    include($file);
}

spl_autoload_register('xClassLoader');
?>
