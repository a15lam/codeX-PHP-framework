<?PHP
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
require_once('codeX.ini.php');
require_once(APP_PATH.'config/config.ini.php');
if(!session_id()){
    session_start();
}
try{
    $X = new \X\engine\X;
    $X->G = new \X\engine\Get($X);
    $X->P = new \X\engine\Post($X);
    
    $X->Rewrite = new \X\engine\Rewrite($X);
    $X->Rewrite->setRootModuleControllerAction();
    $X->Rewrite->setXGet();
    
    if(DB_TYPE){
        \X\engine\DB::$X = $X;
        if(DB_TYPE=='mongo'){
            $X->mongo = \X\engine\DB::getInstance(DB_TYPE);
            $X->db = $X->mongo->selectDB(\X\engine\DB::$db);
        }
        else{
            $X->db = \X\engine\DB::getInstance(DB_TYPE);
        }
        
    }
    
    $X->C = new \X\engine\Common($X);
    $X->T = new \X\engine\Template($X);
    $X->Router = new \X\engine\Router($X);
    $X->Router->load();
    
    unset($X);
}
catch(\Exception $e){
    echo $e->getMessage();
}
?>
