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
//Error reporting modes
error_reporting(E_ALL ^ E_NOTICE);

define('MODULE_DIR', 'modules');
define('CONTROLLER_DIR', 'controller');
define('VIEW_DIR', 'view');

//Defining framework paths
define('X_PATH', realpath(dirname(__FILE__).'/../').'/');
define('X_ENGINE_PATH', realpath(dirname(__FILE__).'/../engine/').'/');
define('X_INCLUDE_PATH', realpath(dirname(__FILE__).'/../includes/').'/');
define('X_MODULE_PATH', realpath(dirname(__FILE__).'/../'.MODULE_DIR.'/').'/');

//Defining application paths
define('APP_PATH', realpath(dirname(__FILE__).'/../../App/').'/');
define('APP_MODULE_PATH', realpath(dirname(__FILE__).'/../../App/'.MODULE_DIR.'/').'/');
define('APP_INCLUDE_PATH', realpath(dirname(__FILE__).'/../../App/includes/').'/');
define('APP_PUBLIC_PATH', realpath(dirname(__FILE__).'/../../public/').'/');

define('ENVIRONMENT', 'DEV');
define('APP_ROOT', 'App');
define('APP_DEFAULT_MODULE', 'main');
define('APP_DEFAULT_CONTROLLER_CLASS_NAME', '\App\\'.MODULE_DIR.'\main\\'.CONTROLLER_DIR.'\IndexController');
define('APP_DEFAULT_ACTION_NAME', 'index');
define('OPEN_CONTROLLER_ACTION', serialize(
        array(
            '\App\\'.MODULE_DIR.'\main\\'.CONTROLLER_DIR.'\IndexController::index'
        )
    )
);
define('BASE_TOUCH_VIEW', X_INCLUDE_PATH."views/baseSenchaTouchView.php");
define('BASE_STANDARD_VIEW', X_INCLUDE_PATH."views/baseJQueryView.php");
define('BASE_PLAIN_VIEW', X_INCLUDE_PATH.'views/basePlainView.php');

require_once('xClassLoader.php');
?>
